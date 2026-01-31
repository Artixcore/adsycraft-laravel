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
        'business_portfolio_id',
        'page_id',
        'page_name',
        'page_access_token',
        'ig_business_id',
        'ig_username',
        'selected',
        'type',
        'meta_id',
        'name',
        'access_token',
        'token_expires_at',
    ];

    protected $hidden = [
        'access_token',
        'page_access_token',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'page_access_token' => 'encrypted',
            'token_expires_at' => 'datetime',
            'selected' => 'boolean',
        ];
    }

    public function getPageAccessTokenMaskedAttribute(): ?string
    {
        if (! $this->page_access_token) {
            return null;
        }
        $raw = $this->page_access_token;
        return strlen($raw) >= 4 ? '••••'.substr($raw, -4) : '••••••••';
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
