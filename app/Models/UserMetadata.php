<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMetadata extends Model
{
    public const REFERENCE_ENV_FILE = 'env_file';

    public const REFERENCE_CONFIG_FILE = 'config_file';

    public const REFERENCE_CUSTOM = 'custom';

    protected $table = 'user_metadata';

    protected $fillable = [
        'user_id',
        'workspace_id',
        'reference_type',
        'key',
        'value',
        'tags',
        'description',
    ];

    protected $hidden = [
        'value',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
            'tags' => 'array',
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
}
