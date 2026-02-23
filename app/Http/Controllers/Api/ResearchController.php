<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\MetaAsset;
use App\Models\ProductResearch;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function trigger(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $request->validate([
            'meta_asset_id' => ['required', 'integer', 'exists:meta_assets,id'],
        ]);

        $asset = MetaAsset::find($request->input('meta_asset_id'));
        if (! $asset || $asset->business_account_id !== $business->id) {
            abort(422, 'Meta asset does not belong to this business.');
        }

        ProductResearch::updateOrCreate(
            [
                'meta_asset_id' => $asset->id,
                'business_account_id' => $business->id,
                'product_name' => 'Stub product',
            ],
            [
                'description' => 'Stub research result',
                'sources' => ['about', 'stub'],
                'confidence' => 0.5,
            ]
        );

        return ApiResponse::success(['meta_asset_id' => $asset->id], 'Research triggered.', null, 202);
    }

    public function results(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'meta_asset_id' => ['sometimes', 'integer', 'exists:meta_assets,id'],
        ]);

        $query = ProductResearch::where('business_account_id', $business->id)->orderByDesc('updated_at');

        if ($request->filled('meta_asset_id')) {
            $query->where('meta_asset_id', $request->input('meta_asset_id'));
        }

        $results = $query->get();

        return ApiResponse::success($results);
    }
}
