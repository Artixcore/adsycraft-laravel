<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    public const ACTION_CREATE = 'create';

    public const ACTION_UPDATE = 'update';

    public const ACTION_DELETE = 'delete';

    public const ACTION_PUBLISH = 'publish';

    public const ACTION_SCHEDULE = 'schedule';

    public const ACTION_REPLY = 'reply';

    public const ACTION_CONNECT = 'connect';

    public const ACTION_DISCONNECT = 'disconnect';

    public const ACTION_CAMPAIGN_CREATE = 'campaign_create';

    public const ACTION_CAMPAIGN_UPDATE = 'campaign_update';

    protected $fillable = [
        'user_id',
        'workspace_id',
        'action',
        'resource_type',
        'resource_id',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public static function log(
        string $action,
        ?string $resourceType = null,
        ?string $resourceId = null,
        ?array $details = null,
        ?int $userId = null,
        ?int $workspaceId = null
    ): static {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'workspace_id' => $workspaceId,
            'action' => $action,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'details' => $details,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
