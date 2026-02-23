<?php

namespace App\Services\Meta;

use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaMarketingService
{
    public function __construct(
        private readonly string $graphVersion
    ) {}

    public static function make(): self
    {
        return new self(config('services.meta.graph_version', 'v21.0'));
    }

    /**
     * List ad accounts accessible to the user via the OAuth connection.
     *
     * @return array<int, array{id: string, name: string, currency: string|null, account_status: int|null}>
     */
    public function listAdAccounts(OAuthConnection $connection): array
    {
        if (config('services.meta.stub', false)) {
            return $this->stubAdAccounts();
        }

        $token = $connection->access_token;
        if (! $token) {
            return [];
        }

        $url = "https://graph.facebook.com/{$this->graphVersion}/me/adaccounts";
        $response = Http::withToken($token)->get($url, [
            'fields' => 'id,name,account_status,currency',
        ]);

        if (! $response->successful()) {
            $error = $response->json('error', []);
            Log::warning('Meta Marketing API listAdAccounts failed', [
                'error' => $error['message'] ?? $response->body(),
            ]);
            throw new \RuntimeException(
                $error['message'] ?? 'Failed to fetch ad accounts from Meta.'
            );
        }

        $data = $response->json('data', []);
        $accounts = [];

        foreach ($data as $item) {
            $accounts[] = [
                'id' => (string) ($item['id'] ?? ''),
                'name' => (string) ($item['name'] ?? ''),
                'currency' => $item['currency'] ?? null,
                'account_status' => $item['account_status'] ?? null,
            ];
        }

        return $accounts;
    }

    /**
     * Select an ad account for the business. Stores or updates meta_ad_accounts.
     *
     * @param  array{name?: string, currency?: string, account_status?: int}|null  $accountData
     */
    public function selectAdAccount(BusinessAccount $business, string $adAccountId, ?array $accountData = null): void
    {
        $business->metaAdAccounts()->update(['selected' => false]);

        $account = $business->metaAdAccounts()->firstOrNew(
            ['meta_ad_account_id' => $adAccountId],
            ['meta_ad_account_id' => $adAccountId, 'business_account_id' => $business->id]
        );

        $account->selected = true;
        if ($accountData) {
            $account->name = $accountData['name'] ?? $account->name;
            $account->currency = $accountData['currency'] ?? $account->currency;
            $account->account_status = $accountData['account_status'] ?? $account->account_status;
        }
        $account->save();
    }

    /**
     * @return array<int, array{id: string, name: string, currency: string|null, account_status: int|null}>
     */
    private function stubAdAccounts(): array
    {
        return [
            [
                'id' => 'act_123456789',
                'name' => 'Stub Ad Account',
                'currency' => 'USD',
                'account_status' => 1,
            ],
        ];
    }
}
