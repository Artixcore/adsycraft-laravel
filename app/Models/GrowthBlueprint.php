<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthBlueprint extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'business_account_id',
        'status',
        'payload',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
