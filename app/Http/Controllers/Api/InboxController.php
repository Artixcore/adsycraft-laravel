<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ReplyMessageJob;
use App\Models\BusinessAccount;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function conversations(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'meta_asset_id' => ['sometimes', 'integer', 'exists:meta_assets,id'],
            'archived' => ['sometimes', 'boolean'],
        ]);

        $query = Conversation::query()
            ->whereHas('metaAsset', fn ($q) => $q->where('business_account_id', $business->id))
            ->orderByDesc('updated_at');

        if ($request->filled('meta_asset_id')) {
            $query->where('meta_asset_id', $request->input('meta_asset_id'));
        }
        if ($request->filled('archived')) {
            $query->where('archived', $request->boolean('archived'));
        }

        $conversations = $query->limit(50)->get();

        return response()->json(['data' => $conversations]);
    }

    public function messages(Request $request, BusinessAccount $business, Conversation $conversation): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);
        if ($conversation->metaAsset->business_account_id !== $business->id) {
            abort(404);
        }

        $messages = $conversation->messages()->orderBy('created_at')->paginate(20);

        return response()->json(['data' => $messages->items(), 'meta' => ['current_page' => $messages->currentPage(), 'last_page' => $messages->lastPage()]]);
    }

    public function reply(Request $request, BusinessAccount $business, Conversation $conversation): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);
        if ($conversation->metaAsset->business_account_id !== $business->id) {
            abort(404);
        }

        $request->validate(['text' => ['required', 'string', 'max:1000']]);

        ReplyMessageJob::dispatch($conversation->id, $request->input('text'));

        return response()->json(['message' => 'Reply queued.'], 202);
    }
}
