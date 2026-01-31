<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use App\Services\Meta\MetaOAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetaConnectorController extends Controller
{
    public function __construct(
        private readonly MetaOAuthService $metaOAuthService
    ) {}

    public function authUrl(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $url = $this->metaOAuthService->getAuthUrl($business, $request->user());

        return response()->json(['url' => $url]);
    }

    public function status(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $connection = $business->oauthConnections()->where('provider', OAuthConnection::PROVIDER_META)->first();

        $connected = $connection && $connection->access_token;

        return response()->json([
            'connected' => $connected,
            'connected_at' => $connection?->connected_at?->toIso8601String(),
            'scopes' => $connection?->scopes,
            'token_masked' => $connection ? $connection->token_masked : null,
        ]);
    }

    public function assets(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $assets = $business->metaAssets()->whereNotNull('page_id')->get()->map(function ($asset) {
            return [
                'id' => $asset->id,
                'page_id' => $asset->page_id,
                'page_name' => $asset->page_name,
                'ig_business_id' => $asset->ig_business_id,
                'ig_username' => $asset->ig_username,
                'selected' => $asset->selected,
            ];
        });

        return response()->json(['data' => $assets]);
    }

    public function selectAssets(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $request->validate([
            'page_ids' => ['required', 'array'],
            'page_ids.*' => ['string'],
        ]);

        $pageIds = $request->input('page_ids', []);

        $business->metaAssets()->whereNotNull('page_id')->update(['selected' => false]);
        if (! empty($pageIds)) {
            $business->metaAssets()->whereNotNull('page_id')->whereIn('page_id', $pageIds)->update(['selected' => true]);
        }

        return response()->json(['message' => 'Selection saved.']);
    }

    public function disconnect(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $connection = $business->oauthConnections()->where('provider', OAuthConnection::PROVIDER_META)->first();

        if ($connection) {
            $connection->update([
                'access_token' => null,
                'expires_at' => null,
                'scopes' => null,
                'connected_at' => null,
                'meta_user_id' => null,
            ]);
        }

        $business->metaAssets()->whereNotNull('page_id')->delete();

        return response()->json(['connected' => false]);
    }
}
