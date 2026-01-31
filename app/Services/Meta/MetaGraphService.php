<?php

namespace App\Services\Meta;

use App\Models\BusinessAccount;
use App\Models\MetaAsset;
use Illuminate\Support\Facades\Http;

class MetaGraphService
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $graphVersion
    ) {}

    public static function forToken(string $accessToken): self
    {
        return new self(
            $accessToken,
            config('services.meta.graph_version', 'v21.0')
        );
    }

    public function getMe(): array
    {
        if (config('services.meta.stub', false)) {
            return ['id' => 'stub_user_123', 'name' => 'Stub User'];
        }
        $response = Http::withToken($this->accessToken)->get(
            "https://graph.facebook.com/{$this->graphVersion}/me",
            ['fields' => 'id,name']
        );
        if (! $response->successful()) {
            return [];
        }
        return $response->json();
    }

    public function getBusinesses(): array
    {
        if (config('services.meta.stub', false)) {
            return ['data' => [['id' => 'stub_biz_1', 'name' => 'Stub Business']]];
        }
        $response = Http::withToken($this->accessToken)->get(
            "https://graph.facebook.com/{$this->graphVersion}/me/businesses",
            ['fields' => 'id,name']
        );
        if (! $response->successful()) {
            return ['data' => []];
        }
        return $response->json();
    }

    public function getPages(): array
    {
        if (config('services.meta.stub', false)) {
            return [
                'data' => [
                    [
                        'id' => 'stub_page_1',
                        'name' => 'Stub Page One',
                        'access_token' => 'stub_page_token_1',
                        'instagram_business_account' => ['id' => 'stub_ig_1', 'username' => 'stub_ig_user'],
                    ],
                    [
                        'id' => 'stub_page_2',
                        'name' => 'Stub Page Two',
                        'access_token' => 'stub_page_token_2',
                        'instagram_business_account' => null,
                    ],
                ],
            ];
        }
        $response = Http::withToken($this->accessToken)->get(
            "https://graph.facebook.com/{$this->graphVersion}/me/accounts",
            [
                'fields' => 'id,name,access_token,instagram_business_account{id,username}',
            ]
        );
        if (! $response->successful()) {
            return ['data' => []];
        }
        return $response->json();
    }

    public function storeAssets(BusinessAccount $business, ?array $pagesData = null): void
    {
        $pagesData ??= $this->getPages();
        $data = $pagesData['data'] ?? [];
        $business->metaAssets()->whereNotNull('page_id')->delete();

        foreach ($data as $page) {
            $pageId = (string) ($page['id'] ?? '');
            $pageName = (string) ($page['name'] ?? '');
            $pageAccessToken = $page['access_token'] ?? null;
            $ig = $page['instagram_business_account'] ?? null;
            $igId = $ig['id'] ?? null;
            $igUsername = $ig['username'] ?? null;

            if ($pageId === '') {
                continue;
            }

            $business->metaAssets()->create([
                'business_portfolio_id' => null,
                'page_id' => $pageId,
                'page_name' => $pageName,
                'page_access_token' => $pageAccessToken,
                'ig_business_id' => $igId ? (string) $igId : null,
                'ig_username' => $igUsername ? (string) $igUsername : null,
                'selected' => false,
                'type' => MetaAsset::TYPE_PAGE,
                'meta_id' => $pageId,
                'name' => $pageName,
                'access_token' => $pageAccessToken,
                'token_expires_at' => null,
            ]);
        }
    }
}
