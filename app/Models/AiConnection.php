<?php

namespace App\Models;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiConnection extends Model
{
    use HasFactory;

    public const PROVIDER_OPENAI = 'openai';
    public const PROVIDER_GEMINI = 'gemini';
    public const PROVIDER_GROK = 'grok';

    protected $fillable = [
        'business_account_id',
        'provider',
        'api_key',
        'default_model',
        'is_enabled',
        'is_primary',
        'last_tested_at',
    ];

    protected $hidden = [
        'api_key',
    ];

    protected $appends = [
        'api_key_masked',
    ];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
            'is_enabled' => 'boolean',
            'is_primary' => 'boolean',
            'last_tested_at' => 'datetime',
        ];
    }

    public function getApiKeyMaskedAttribute(): ?string
    {
        try {
            $raw = $this->attributes['api_key'] ?? null;
            if (! $raw) {
                return null;
            }
            $decrypted = $this->fromEncryptedString($raw);

            return '••••' . substr($decrypted, -4);
        } catch (DecryptException) {
            return '••••****';
        }
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', true);
    }
}
