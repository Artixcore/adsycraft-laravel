<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusinessAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'name',
        'niche',
        'website_url',
        'tone',
        'language',
        'timezone',
        'posts_per_day',
        'autopilot_enabled',
        'meta_page_id',
        'settings',
    ];

    protected function casts(): array
    {
        return [
            'autopilot_enabled' => 'boolean',
            'posts_per_day' => 'integer',
            'settings' => 'array',
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

    public function metaAssets(): HasMany
    {
        return $this->hasMany(MetaAsset::class);
    }

    public function contentPillars(): HasMany
    {
        return $this->hasMany(ContentPillar::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function aiConnections(): HasMany
    {
        return $this->hasMany(AiConnection::class);
    }

    public function aiTaskProfiles(): HasMany
    {
        return $this->hasMany(AiTaskProfile::class);
    }

    public function oauthConnections(): HasMany
    {
        return $this->hasMany(OAuthConnection::class);
    }
}
