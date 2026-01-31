<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetaAsset extends Model
{
    use HasFactory;

    public const TYPE_PAGE = 'page';
    public const TYPE_INSTAGRAM_ACCOUNT = 'instagram_account';

    protected $fillable = [
        'business_account_id',
        'type',
        'meta_id',
        'name',
        'access_token',
        'token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'token_expires_at' => 'datetime',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
