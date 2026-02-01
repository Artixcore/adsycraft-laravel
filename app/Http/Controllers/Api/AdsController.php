<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    public function adAccounts(Request $request): JsonResponse
    {
        $request->validate(['workspace_id' => ['sometimes', 'integer', 'exists:workspaces,id']]);

        $workspaceIds = $request->user()->workspaces()->pluck('workspaces.id');
        $query = AdAccount::query()->whereIn('workspace_id', $workspaceIds)->orderBy('name');

        if ($request->filled('workspace_id')) {
            if (! $workspaceIds->contains($request->input('workspace_id'))) {
                abort(403);
            }
            $query->where('workspace_id', $request->input('workspace_id'));
        }

        $accounts = $query->get();

        return response()->json(['data' => $accounts]);
    }

    public function campaigns(Request $request, AdAccount $adAccount): JsonResponse
    {
        $workspace = $adAccount->workspace;
        if (! $workspace || ! $workspace->users()->where('user_id', $request->user()->id)->exists()) {
            abort(403);
        }

        $campaigns = $adAccount->campaigns()->orderBy('name')->get();

        return response()->json(['data' => $campaigns]);
    }
}
