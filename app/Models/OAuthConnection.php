<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthConnection extends Model
{
    use HasFactory;

    public const PROVIDER_META = 'meta';

    protected $fillable = [
        'business_account_id',
        'provider',
        'access_token',
        'expires_at',
        'scopes',
        'connected_at',
        'meta_user_id',
    ];

    protected $hidden = [
        'access_token',
    ];

    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
            'expires_at' => 'datetime',
            'connected_at' => 'datetime',
            'scopes' => 'array',
        ];
    }

    public function getTokenMaskedAttribute(): ?string
    {
        if (! $this->access_token) {
            return null;
        }
        $raw = $this->access_token;
        return strlen($raw) >= 4 ? '••••'.substr($raw, -4) : '••••••••';
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
