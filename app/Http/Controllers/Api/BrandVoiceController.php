<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandVoiceRequest;
use App\Http\Requests\UpdateBrandVoiceRequest;
use App\Models\BrandVoice;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandVoiceController extends Controller
{
    public function index(Request $request, Workspace $workspace): JsonResponse
    {
        $this->ensureUserInWorkspace($request->user(), $workspace);
        $voices = $workspace->brandVoices()->with('metaAsset')->orderBy('meta_asset_id')->get();

        return response()->json(['data' => $voices]);
    }

    public function store(StoreBrandVoiceRequest $request, Workspace $workspace): JsonResponse
    {
        $data = $request->validated();
        $metaAssetId = $data['meta_asset_id'] ?? null;
        if ($metaAssetId) {
            $asset = \App\Models\MetaAsset::find($metaAssetId);
            if (! $asset || $asset->businessAccount->workspace_id !== $workspace->id) {
                abort(422, 'Meta asset does not belong to this workspace.');
            }
        }
        $data['workspace_id'] = $workspace->id;
        $voice = BrandVoice::create($data);

        return response()->json(['data' => $voice->fresh()], 201);
    }

    public function show(Request $request, Workspace $workspace, BrandVoice $brandVoice): JsonResponse
    {
        $this->ensureUserInWorkspace($request->user(), $workspace);
        $this->ensureBrandVoiceInWorkspace($brandVoice, $workspace);

        return response()->json(['data' => $brandVoice->load('metaAsset')]);
    }

    public function update(UpdateBrandVoiceRequest $request, Workspace $workspace, BrandVoice $brandVoice): JsonResponse
    {
        $this->ensureBrandVoiceInWorkspace($brandVoice, $workspace);
        $brandVoice->update($request->validated());

        return response()->json(['data' => $brandVoice->fresh()]);
    }

    public function destroy(Request $request, Workspace $workspace, BrandVoice $brandVoice): JsonResponse
    {
        $this->ensureUserInWorkspace($request->user(), $workspace);
        $this->ensureBrandVoiceInWorkspace($brandVoice, $workspace);
        $brandVoice->delete();

        return response()->json(null, 204);
    }

    private function ensureUserInWorkspace($user, Workspace $workspace): void
    {
        if (! $workspace->users()->where('user_id', $user->id)->exists()) {
            abort(403, 'Unauthorized.');
        }
    }

    private function ensureBrandVoiceInWorkspace(BrandVoice $brandVoice, Workspace $workspace): void
    {
        if ($brandVoice->workspace_id !== $workspace->id) {
            abort(404);
        }
    }
}
