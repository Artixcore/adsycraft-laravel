<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessAccountRequest;
use App\Http\Requests\UpdateBusinessAccountRequest;
use App\Jobs\GenerateDailyContentJob;
use App\Models\BusinessAccount;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->businessAccounts()->orderBy('name');
        if ($request->filled('workspace_id')) {
            $workspaceId = (int) $request->input('workspace_id');
            if ($request->user()->workspaces()->where('workspaces.id', $workspaceId)->exists()) {
                $query->where('workspace_id', $workspaceId);
            }
        }
        $accounts = $query->get();
        $total = $accounts->count();
        $end = $total > 0 ? $total - 1 : 0;

        return response()->json(['data' => $accounts])
            ->header('Content-Range', "businesses 0-{$end}/{$total}");
    }

    public function store(StoreBusinessAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $workspaceId = $data['workspace_id'] ?? null;
        if ($workspaceId && ! $request->user()->workspaces()->where('workspaces.id', $workspaceId)->exists()) {
            abort(403, 'You do not have access to this workspace.');
        }
        $account = $request->user()->businessAccounts()->create($data);

        return ApiResponse::success($account, 'Business created.', null, 201);
    }

    public function show(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        return ApiResponse::success($business);
    }

    public function update(UpdateBusinessAccountRequest $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $business->update($request->validated());

        return ApiResponse::success($business->fresh(), 'Business updated.');
    }

    public function destroy(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'delete', $business);

        $business->delete();

        return ApiResponse::success(null, 'Business deleted.', null, 200);
    }

    public function toggleAutopilot(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $business->update(['autopilot_enabled' => ! $business->autopilot_enabled]);

        return ApiResponse::success($business->fresh(), 'Autopilot updated.');
    }

    public function generateToday(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $date = $request->input('date', Carbon::today($business->timezone)->toDateString());
        GenerateDailyContentJob::dispatch($business->id, $date);

        return ApiResponse::success(null, 'Job dispatched.', null, 202);
    }
}
