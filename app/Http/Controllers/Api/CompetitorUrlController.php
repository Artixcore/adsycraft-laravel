<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\CompetitorUrl;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompetitorUrlController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $urls = $business->competitorUrls()->orderBy('sort_order')->get();

        return ApiResponse::success($urls);
    }

    public function store(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $count = $business->competitorUrls()->count();
        if ($count >= 10) {
            return ApiResponse::error('Maximum 10 competitor URLs allowed.', null, 422);
        }

        $validated = $request->validate([
            'url' => ['required', 'string', 'url', 'max:500'],
            'page_name' => ['nullable', 'string', 'max:255'],
        ]);

        $url = $business->competitorUrls()->create([
            'url' => $validated['url'],
            'page_name' => $validated['page_name'] ?? null,
            'sort_order' => $count,
        ]);

        return ApiResponse::success($url, 'Competitor URL added.', null, 201);
    }

    public function destroy(Request $request, BusinessAccount $business, CompetitorUrl $competitorUrl): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        if ($competitorUrl->business_account_id !== $business->id) {
            abort(404);
        }

        $competitorUrl->delete();

        return ApiResponse::success(null, 'Competitor URL removed.');
    }
}
