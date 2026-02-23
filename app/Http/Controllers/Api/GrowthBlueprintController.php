<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateGrowthBlueprintJob;
use App\Models\BusinessAccount;
use App\Models\GrowthBlueprint;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrowthBlueprintController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $blueprints = $business->growthBlueprints()
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(fn (GrowthBlueprint $b) => [
                'id' => $b->id,
                'status' => $b->status,
                'created_at' => $b->created_at->toIso8601String(),
                'updated_at' => $b->updated_at->toIso8601String(),
            ]);

        return ApiResponse::success($blueprints);
    }

    public function show(Request $request, BusinessAccount $business, GrowthBlueprint $growthBlueprint): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        if ($growthBlueprint->business_account_id !== $business->id) {
            abort(404);
        }

        return ApiResponse::success([
            'id' => $growthBlueprint->id,
            'status' => $growthBlueprint->status,
            'payload' => $growthBlueprint->payload,
            'error_message' => $growthBlueprint->error_message,
            'created_at' => $growthBlueprint->created_at->toIso8601String(),
            'updated_at' => $growthBlueprint->updated_at->toIso8601String(),
        ]);
    }

    public function store(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        GenerateGrowthBlueprintJob::dispatch($business->id);

        return ApiResponse::success(
            ['message' => 'Growth Blueprint generation started. Poll the index or show endpoint for status.'],
            'Job dispatched.',
            null,
            202
        );
    }
}
