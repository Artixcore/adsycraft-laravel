<?php

namespace App\Services\Meta;

use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

class MetaOAuthService
{
    private const STATE_TTL_SECONDS = 600;

    public function getAuthUrl(BusinessAccount $business, User $user): string
    {
        $stub = config('services.meta.stub', false);
        if ($stub) {
            $state = $this->encodeState($user->id, $business->id);
            return url('/connectors/meta/callback').'?code=stub_'.base64_encode($state).'&state='.urlencode($state);
        }

        $state = $this->encodeState($user->id, $business->id);
        $version = config('services.meta.graph_version', 'v21.0');
        $base = 'https://www.facebook.com/'.$version.'/dialog/oauth';
        $params = http_build_query([
            'client_id' => config('services.meta.app_id'),
            'redirect_uri' => config('services.meta.redirect_uri'),
            'scope' => implode(',', [
                'pages_show_list',
                'pages_manage_metadata',
                'instagram_basic_profile',
            ]),
            'state' => $state,
            'response_type' => 'code',
        ]);

        return $base.'?'.$params;
    }

    public function handleCallback(string $code, string $state): array
    {
        $payload = $this->decodeState($state);
        if (! $payload) {
            throw new \InvalidArgumentException('Invalid or expired state.');
        }

        $business = BusinessAccount::find($payload['business_account_id']);
        if (! $business || $business->user_id != $payload['user_id']) {
            throw new \InvalidArgumentException('Business not found or access denied.');
        }

        $stub = config('services.meta.stub', false);
        if ($stub) {
            if (str_starts_with($code, 'stub_')) {
                $connection = $this->getOrCreateConnection($business);
                $connection->update([
                    'access_token' => 'stub_token_'.bin2hex(random_bytes(8)),
                    'expires_at' => now()->addDays(60),
                    'scopes' => ['pages_show_list', 'pages_manage_metadata', 'instagram_basic_profile'],
                    'connected_at' => now(),
                    'meta_user_id' => 'stub_user_'.$payload['user_id'],
                ]);
                return ['connection' => $connection->fresh(), 'access_token' => $connection->access_token];
            }
            throw new \InvalidArgumentException('Invalid stub code.');
        }

        $tokenResponse = $this->exchangeCodeForToken($code);
        if (empty($tokenResponse['access_token'])) {
            throw new \RuntimeException('Failed to exchange code for token.');
        }

        $accessToken = $tokenResponse['access_token'];
        $longLived = $this->exchangeForLongLivedToken($accessToken);
        if ($longLived) {
            $accessToken = $longLived;
        }

        $connection = $this->getOrCreateConnection($business);
        $connection->update([
            'access_token' => $accessToken,
            'expires_at' => isset($tokenResponse['expires_in']) ? now()->addSeconds((int) $tokenResponse['expires_in']) : null,
            'scopes' => isset($tokenResponse['granted_scopes']) ? explode(',', $tokenResponse['granted_scopes']) : null,
            'connected_at' => now(),
            'meta_user_id' => $this->fetchMetaUserId($accessToken),
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

    private function encodeState(int $userId, int $businessAccountId): string
    {
        $payload = [
            'user_id' => $userId,
            'business_account_id' => $businessAccountId,
            'ts' => time(),
        ];
        return base64_encode(Crypt::encryptString(json_encode($payload)));
    }

    private function decodeState(string $state): ?array
    {
        try {
            $json = Crypt::decryptString(base64_decode($state, true) ?: '');
            $payload = json_decode($json, true);
            if (! $payload || ! isset($payload['user_id'], $payload['business_account_id'], $payload['ts'])) {
                return null;
            }
            if (time() - $payload['ts'] > self::STATE_TTL_SECONDS) {
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
}
