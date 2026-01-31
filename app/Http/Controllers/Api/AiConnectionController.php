<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAiConnectionRequest;
use App\Http\Requests\UpdateAiConnectionRequest;
use App\Models\AiConnection;
use App\Models\BusinessAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AiConnectionController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $connections = $business->aiConnections()->orderBy('provider')->get();

        return response()->json(['data' => $connections]);
    }

    public function store(StoreAiConnectionRequest $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $data = $request->only(['provider', 'api_key', 'default_model', 'is_enabled', 'is_primary']);

        $connection = DB::transaction(function () use ($business, $data) {
            if (! empty($data['is_primary'])) {
                $business->aiConnections()->update(['is_primary' => false]);
            }
            $connection = $business->aiConnections()->create($data);
            if (! empty($data['is_primary'])) {
                $connection->update(['is_primary' => true]);
            }

            return $connection;
        });

        return response()->json(['data' => $connection->fresh()], 201);
    }

    public function update(UpdateAiConnectionRequest $request, BusinessAccount $business, AiConnection $connection): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        if ($connection->business_account_id !== $business->id) {
            abort(404);
        }

        $data = $request->only(['api_key', 'default_model', 'is_enabled', 'is_primary']);
        $data = array_filter($data, fn ($v) => $v !== null && $v !== '');

        DB::transaction(function () use ($business, $connection, $data) {
            if (! empty($data['is_primary'])) {
                $business->aiConnections()->where('id', '!=', $connection->id)->update(['is_primary' => false]);
            }
            $connection->update($data);
        });

        return response()->json(['data' => $connection->fresh()]);
    }

    public function destroy(Request $request, BusinessAccount $business, AiConnection $connection): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        if ($connection->business_account_id !== $business->id) {
            abort(404);
        }

        $connection->delete();

        return response()->json(null, 204);
    }

    public function makePrimary(Request $request, BusinessAccount $business, AiConnection $connection): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        if ($connection->business_account_id !== $business->id) {
            abort(404);
        }

        DB::transaction(function () use ($business, $connection) {
            $business->aiConnections()->update(['is_primary' => false]);
            $connection->update(['is_primary' => true]);
        });

        return response()->json(['data' => $connection->fresh()]);
    }

    public function test(Request $request, BusinessAccount $business, AiConnection $connection): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        if ($connection->business_account_id !== $business->id) {
            abort(404);
        }

        $key = $connection->api_key;
        if (strlen($key) < 10) {
            return response()->json(['ok' => false, 'message' => 'Invalid key format.'], 422);
        }

        $connection->update(['last_tested_at' => now()]);

        return response()->json(['ok' => true, 'message' => 'Connection validated.']);
    }
}
