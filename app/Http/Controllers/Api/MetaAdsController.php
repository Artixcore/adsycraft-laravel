<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use App\Services\Meta\MetaMarketingService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetaAdsController extends Controller
{
    public function adAccounts(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $connection = $business->oauthConnections()
            ->where('provider', OAuthConnection::PROVIDER_META)
            ->whereNotNull('access_token')
            ->first();

        if (! $connection) {
            return ApiResponse::error(
                'Connect Meta in Connectors to access ad accounts.',
                null,
                401
            );
        }

        try {
            $accounts = MetaMarketingService::make()->listAdAccounts($connection);
        } catch (\RuntimeException $e) {
            return ApiResponse::error($e->getMessage(), null, 500);
        }

        $selectedIds = $business->metaAdAccounts()
            ->where('selected', true)
            ->pluck('meta_ad_account_id')
            ->toArray();

        $items = array_map(function (array $acc) use ($selectedIds) {
            return [
                'id' => $acc['id'],
                'name' => $acc['name'],
                'currency' => $acc['currency'],
                'account_status' => $acc['account_status'],
                'selected' => in_array($acc['id'], $selectedIds, true),
            ];
        }, $accounts);

        return ApiResponse::success($items);
    }

    public function selectAdAccount(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $validated = $request->validate([
            'ad_account_id' => ['required', 'string', 'max:50'],
            'name' => ['nullable', 'string', 'max:255'],
            'currency' => ['nullable', 'string', 'size:3'],
            'account_status' => ['nullable', 'integer'],
        ]);

        $accountData = array_filter([
            'name' => $validated['name'] ?? null,
            'currency' => $validated['currency'] ?? null,
            'account_status' => $validated['account_status'] ?? null,
        ]);

        try {
            MetaMarketingService::make()->selectAdAccount(
                $business,
                $validated['ad_account_id'],
                $accountData ?: null
            );
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 500);
        }

        return ApiResponse::success(null, 'Ad account selected.');
    }
}
