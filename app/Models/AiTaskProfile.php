<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiTaskProfile extends Model
{
    use HasFactory;

    public const TASK_RESEARCH = 'research';
    public const TASK_COMPETITOR = 'competitor';
    public const TASK_DAILY_POSTS = 'daily_posts';
    public const TASK_TRENDING = 'trending';

    protected $fillable = [
        'business_account_id',
        'task',
        'provider',
        'model',
        'temperature',
        'max_tokens',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'decimal:2',
            'max_tokens' => 'integer',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
