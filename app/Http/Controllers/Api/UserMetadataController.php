<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserMetadataRequest;
use App\Http\Requests\UpdateUserMetadataRequest;
use App\Models\UserMetadata;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserMetadataController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = UserMetadata::where('user_id', $request->user()->id)->orderBy('key');
        if ($request->filled('workspace_id')) {
            $workspaceId = (int) $request->input('workspace_id');
            if ($request->user()->workspaces()->where('workspaces.id', $workspaceId)->exists()) {
                $query->where('workspace_id', $workspaceId);
            }
        }
        if ($request->filled('reference_type')) {
            $query->where('reference_type', $request->input('reference_type'));
        }
        $items = $query->get()->makeHidden(['value']);

        return response()->json(['data' => $items]);
    }

    public function store(StoreUserMetadataRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        if (! empty($data['workspace_id']) && ! $request->user()->workspaces()->where('workspaces.id', $data['workspace_id'])->exists()) {
            abort(403, 'You do not have access to this workspace.');
        }
        $metadata = UserMetadata::create($data);

        return response()->json(['data' => $metadata], 201);
    }

    public function show(Request $request, UserMetadata $metadata): JsonResponse
    {
        if ($metadata->user_id !== $request->user()->id) {
            abort(404);
        }

        return response()->json(['data' => $metadata]);
    }

    public function update(UpdateUserMetadataRequest $request, UserMetadata $metadata): JsonResponse
    {
        $metadata->update($request->validated());

        return response()->json(['data' => $metadata->fresh()]);
    }

    public function destroy(Request $request, UserMetadata $metadata): JsonResponse
    {
        if ($metadata->user_id !== $request->user()->id) {
            abort(404);
        }
        $metadata->delete();

        return response()->json(null, 204);
    }
}
