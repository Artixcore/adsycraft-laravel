<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'status' => ['sometimes', 'string', 'in:scheduled,published,failed,draft,publishing'],
        ]);

        $query = $business->posts()->orderBy('scheduled_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return response()->json(['data' => $query->get()]);
    }

    public function calendar(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $query = $business->posts()->orderBy('scheduled_at');

        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', Carbon::parse($request->input('from'))->startOfDay());
        }
        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', Carbon::parse($request->input('to'))->endOfDay());
        }

        return response()->json(['data' => $query->get()]);
    }
}
