<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessAccountRequest;
use App\Http\Requests\UpdateBusinessAccountRequest;
use App\Jobs\GenerateDailyContentJob;
use App\Models\BusinessAccount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessAccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $accounts = $request->user()
            ->businessAccounts()
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $accounts]);
    }

    public function store(StoreBusinessAccountRequest $request): JsonResponse
    {
        $account = $request->user()->businessAccounts()->create($request->validated());

        return response()->json(['data' => $account], 201);
    }

    public function show(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        return response()->json(['data' => $business]);
    }

    public function update(UpdateBusinessAccountRequest $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $business->update($request->validated());

        return response()->json(['data' => $business->fresh()]);
    }

    public function destroy(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'delete', $business);

        $business->delete();

        return response()->json(null, 204);
    }

    public function toggleAutopilot(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $business->update(['autopilot_enabled' => ! $business->autopilot_enabled]);

        return response()->json(['data' => $business->fresh()]);
    }

    public function generateToday(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $date = $request->input('date', Carbon::today($business->timezone)->toDateString());
        GenerateDailyContentJob::dispatch($business->id, $date);

        return response()->json([
            'message' => 'GenerateDailyContentJob dispatched for ' . $date,
        ], 202);
    }
}
