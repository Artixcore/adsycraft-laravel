<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessAccount;
use App\Models\OAuthConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetaConnectorController extends Controller
{
    public function status(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $connection = $business->oauthConnections()->firstOrCreate(
            ['provider' => OAuthConnection::PROVIDER_META],
            ['provider' => OAuthConnection::PROVIDER_META]
        );

        return response()->json([
            'connected' => (bool) $connection->access_token,
            'connected_at' => $connection->connected_at?->toIso8601String(),
            'scopes' => $connection->scopes,
        ]);
    }

    public function connect(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);

        $url = 'https://placeholder-meta-oauth.example.com/connect?state=' . $business->id;

        return response()->json(['url' => $url]);
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
            ]);
        }

        return response()->json(['connected' => false]);
    }
}
