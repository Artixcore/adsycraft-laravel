<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Models\Workspace;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $workspaces = $request->user()
            ->workspaces()
            ->orderBy('name')
            ->get();
        $total = $workspaces->count();
        $end = $total > 0 ? $total - 1 : 0;

        return ApiResponse::success($workspaces, 'OK', ['total' => $total])
            ->header('Content-Range', "workspaces 0-{$end}/{$total}");
    }

    public function store(StoreWorkspaceRequest $request): JsonResponse
    {
        $workspace = Workspace::createWithSlug($request->validated());
        $workspace->users()->attach($request->user()->id);

        return ApiResponse::success($workspace->fresh(), 'Workspace created.', null, 201);
    }

    public function show(Request $request, Workspace $workspace): JsonResponse
    {
        $this->ensureUserInWorkspace($request->user(), $workspace);

        return ApiResponse::success($workspace->load('businessAccounts'));
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace): JsonResponse
    {
        $workspace->update($request->validated());

        return ApiResponse::success($workspace->fresh(), 'Workspace updated.');
    }

    public function destroy(Request $request, Workspace $workspace): JsonResponse
    {
        $this->ensureUserInWorkspace($request->user(), $workspace);
        $workspace->delete();

        return ApiResponse::success(null, 'Workspace deleted.', null, 200);
    }

    private function ensureUserInWorkspace($user, Workspace $workspace): void
    {
        if (! $workspace->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'Unauthorized.');
        }
    }
}
