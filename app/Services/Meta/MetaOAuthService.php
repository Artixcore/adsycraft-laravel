<?php

namespace App\Services\Meta;

use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MetaOAuthService
{
    private const STATE_TTL_SECONDS = 600;

    private const SCOPES = [
        'pages_show_list',
        'pages_read_engagement',
    ];

    public function getAuthUrl(Request $request, BusinessAccount $business, User $user): string
    {
        $stub = config('services.meta.stub', false);
        if ($stub) {
            $state = $this->encodeState($user->id, $business->id, null);
            $request->session()->put('meta_oauth_nonce', 'stub_nonce');

            return url('/connectors/meta/callback').'?code=stub_'.base64_encode($state).'&state='.urlencode($state);
        }

        $nonce = Str::random(32);
        $request->session()->put('meta_oauth_nonce', $nonce);
        $state = $this->encodeState($user->id, $business->id, $nonce);

        $version = config('services.meta.graph_version', 'v21.0');
        $base = 'https://www.facebook.com/'.$version.'/dialog/oauth';
        $params = http_build_query([
            'client_id' => config('services.meta.app_id'),
            'redirect_uri' => config('services.meta.redirect_uri'),
            'scope' => implode(',', self::SCOPES),
            'state' => $state,
            'response_type' => 'code',
        ]);

        return $base.'?'.$params;
    }

    public function handleCallback(Request $request, string $code, string $state): array
    {
        $correlationId = $request->header('X-Request-ID') ?? Str::uuid()->toString();
        $this->logSafe($correlationId, 'meta_oauth_callback_start', ['has_code' => ! empty($code), 'has_state' => ! empty($state)]);

        $payload = $this->decodeState($request, $state);
        if (! $payload) {
            $this->logSafe($correlationId, 'meta_oauth_state_invalid', []);
            throw new \InvalidArgumentException('Invalid or expired state.');
        }

        $business = BusinessAccount::find($payload['business_account_id']);
        if (! $business || (int) $business->user_id !== (int) $payload['user_id']) {
            $this->logSafe($correlationId, 'meta_oauth_business_mismatch', ['business_id' => $payload['business_account_id'] ?? null]);
            throw new \InvalidArgumentException('Business not found or access denied.');
        }

        $stub = config('services.meta.stub', false);
        if ($stub) {
            if (str_starts_with($code, 'stub_')) {
                $connection = $this->getOrCreateConnection($business);
                $connection->update([
                    'access_token' => 'stub_token_'.bin2hex(random_bytes(8)),
                    'expires_at' => now()->addDays(60),
                    'scopes' => self::SCOPES,
                    'connected_at' => now(),
                    'meta_user_id' => 'stub_user_'.$payload['user_id'],
                ]);
                $this->logSafe($correlationId, 'meta_oauth_stub_connected', ['connection_id' => $connection->id]);

                return ['connection' => $connection->fresh(), 'access_token' => $connection->access_token];
            }
            throw new \InvalidArgumentException('Invalid stub code.');
        }

        $this->logSafe($correlationId, 'meta_oauth_exchanging_code', []);
        $tokenResponse = $this->exchangeCodeForToken($code);
        if (empty($tokenResponse['access_token'])) {
            $this->logSafe($correlationId, 'meta_oauth_exchange_failed', []);
            throw new \RuntimeException('Failed to exchange code for token.');
        }

        $accessToken = $tokenResponse['access_token'];
        $longLived = $this->exchangeForLongLivedToken($accessToken);
        if ($longLived) {
            $accessToken = $longLived;
            $this->logSafe($correlationId, 'meta_oauth_long_lived_obtained', []);
        }

        $debugData = $this->debugToken($accessToken);
        $expiresAt = isset($debugData['expires_at']) ? \Carbon\Carbon::createFromTimestamp($debugData['expires_at']) : null;
        $scopes = isset($debugData['scopes']) ? $debugData['scopes'] : (isset($tokenResponse['granted_scopes']) ? explode(',', $tokenResponse['granted_scopes']) : null);
        if (! $expiresAt && isset($tokenResponse['expires_in'])) {
            $expiresAt = now()->addSeconds((int) $tokenResponse['expires_in']);
        }

        $metaUserId = $debugData['user_id'] ?? $this->fetchMetaUserId($accessToken);

        $connection = $this->getOrCreateConnection($business);
        $connection->update([
            'access_token' => $accessToken,
            'expires_at' => $expiresAt,
            'scopes' => $scopes,
            'connected_at' => now(),
            'meta_user_id' => $metaUserId,
        ]);

        $this->logSafe($correlationId, 'meta_oauth_connected', [
            'connection_id' => $connection->id,
            'meta_user_id' => $metaUserId,
            'expires_at' => $expiresAt?->toIso8601String(),
        ]);

        return ['connection' => $connection->fresh(), 'access_token' => $connection->access_token];
    }

    public function exchangeForLongLivedToken(string $shortToken): ?string
    {
        $version = config('services.meta.graph_version', 'v21.0');
        $url = "https://graph.facebook.com/{$version}/oauth/access_token";
        $response = Http::get($url, [
            'grant_type' => 'fb_exchange_token',
            'client_id' => config('services.meta.app_id'),
            'client_secret' => config('services.meta.app_secret'),
            'fb_exchange_token' => $shortToken,
        ]);

        if (! $response->successful()) {
            return null;
        }
        $data = $response->json();

        return $data['access_token'] ?? null;
    }

    public function debugToken(string $token): ?array
    {
        $version = config('services.meta.graph_version', 'v21.0');
        $response = Http::get("https://graph.facebook.com/{$version}/debug_token", [
            'input_token' => $token,
            'access_token' => config('services.meta.app_id').'|'.config('services.meta.app_secret'),
        ]);
        if (! $response->successful()) {
            return null;
        }

        return $response->json('data');
    }

    private function encodeState(int $userId, int $businessAccountId, ?string $nonce): string
    {
        $payload = [
            'user_id' => $userId,
            'business_account_id' => $businessAccountId,
            'nonce' => $nonce ?? Str::random(32),
            'issued_at' => time(),
        ];
        $payloadJson = json_encode($payload);
        $secret = config('services.meta.state_secret', config('app.key'));
        $signature = hash_hmac('sha256', $payloadJson, $secret, true);

        return base64_encode($payloadJson).'.'.base64_encode($signature);
    }

    private function decodeState(Request $request, string $state): ?array
    {
        try {
            $parts = explode('.', $state);
            if (count($parts) !== 2) {
                return null;
            }
            [$payloadB64, $signatureB64] = $parts;
            $payloadJson = base64_decode($payloadB64, true);
            $signature = base64_decode($signatureB64, true);
            if (! $payloadJson || ! $signature) {
                return null;
            }
            $secret = config('services.meta.state_secret', config('app.key'));
            $expectedSignature = hash_hmac('sha256', $payloadJson, $secret, true);
            if (! hash_equals($expectedSignature, $signature)) {
                return null;
            }
            $payload = json_decode($payloadJson, true);
            if (! $payload || ! isset($payload['user_id'], $payload['business_account_id'], $payload['nonce'], $payload['issued_at'])) {
                return null;
            }
            if (time() - $payload['issued_at'] > self::STATE_TTL_SECONDS) {
                return null;
            }
            $sessionNonce = $request->session()->pull('meta_oauth_nonce');
            if ($sessionNonce === null || ! hash_equals($payload['nonce'], $sessionNonce)) {
                return null;
            }

            return $payload;
        } catch (\Throwable) {
            return null;
        }
    }

    private function exchangeCodeForToken(string $code): array
    {
        $version = config('services.meta.graph_version', 'v21.0');
        $response = Http::get("https://graph.facebook.com/{$version}/oauth/access_token", [
            'client_id' => config('services.meta.app_id'),
            'client_secret' => config('services.meta.app_secret'),
            'redirect_uri' => config('services.meta.redirect_uri'),
            'code' => $code,
        ]);
        if (! $response->successful()) {
            return [];
        }

        return $response->json();
    }

    private function fetchMetaUserId(string $accessToken): ?string
    {
        $version = config('services.meta.graph_version', 'v21.0');
        $response = Http::withToken($accessToken)->get("https://graph.facebook.com/{$version}/me", [
            'fields' => 'id',
        ]);
        if (! $response->successful()) {
            return null;
        }

        return $response->json('id');
    }

    private function getOrCreateConnection(BusinessAccount $business): OAuthConnection
    {
        return $business->oauthConnections()->firstOrCreate(
            ['provider' => OAuthConnection::PROVIDER_META],
            ['provider' => OAuthConnection::PROVIDER_META]
        );
    }

    private function logSafe(string $correlationId, string $event, array $context): void
    {
        Log::info("meta_oauth: {$event}", array_merge($context, ['correlation_id' => $correlationId]));
    }
}
