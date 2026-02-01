<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\PageInsight;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PageInsightController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'meta_asset_id' => ['sometimes', 'integer', 'exists:meta_assets,id'],
            'period' => ['sometimes', 'string', 'in:day,week,month'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
        ]);

        $query = PageInsight::where('business_account_id', $business->id)->orderByDesc('period_date');

        if ($request->filled('meta_asset_id')) {
            $query->where('meta_asset_id', $request->input('meta_asset_id'));
        }
        if ($request->filled('period')) {
            $query->where('period', $request->input('period'));
        }
        if ($request->filled('from')) {
            $query->whereDate('period_date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('period_date', '<=', $request->input('to'));
        }

        $insights = $query->limit(100)->get();

        return response()->json(['data' => $insights]);
    }
}
