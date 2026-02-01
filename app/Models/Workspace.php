<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use App\Enums\SubscriptionTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subscription_tier',
        'subscription_status',
        'subscription_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'subscription_tier' => SubscriptionTier::class,
            'subscription_status' => SubscriptionStatus::class,
            'subscription_expires_at' => 'datetime',
        ];
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_user')
            ->withTimestamps();
    }

    public function businessAccounts(): HasMany
    {
        return $this->hasMany(BusinessAccount::class, 'workspace_id');
    }

    public function brandVoices(): HasMany
    {
        return $this->hasMany(BrandVoice::class, 'workspace_id');
    }

    public static function createWithSlug(array $attributes): static
    {
        $slug = $attributes['slug'] ?? Str::slug($attributes['name'] ?? 'workspace');
        $base = $slug;
        $i = 0;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }
        $attributes['slug'] = $slug;

        return static::create($attributes);
    }
}
