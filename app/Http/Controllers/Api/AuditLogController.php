<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Workspace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'workspace_id' => ['sometimes', 'integer', 'exists:workspaces,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'action' => ['sometimes', 'string', 'max:50'],
            'from' => ['sometimes', 'date'],
            'to' => ['sometimes', 'date', 'after_or_equal:from'],
        ]);

        $query = AuditLog::query()->with('user:id,name,email')->orderByDesc('created_at');

        if ($request->filled('workspace_id')) {
            $workspace = Workspace::find($request->input('workspace_id'));
            if (! $workspace || ! $workspace->users()->where('user_id', $request->user()->id)->exists()) {
                abort(403, 'You do not have access to this workspace.');
            }
            $query->where('workspace_id', $request->input('workspace_id'));
        } else {
            $workspaceIds = $request->user()->workspaces()->pluck('workspaces.id');
            $query->whereIn('workspace_id', $workspaceIds);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->input('to'));
        }

        $logs = $query->limit(100)->get();
        $total = $logs->count();
        $end = $total > 0 ? $total - 1 : 0;

        return response()->json(['data' => $logs])
            ->header('Content-Range', "audit-logs 0-{$end}/{$total}");
    }
}
