<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BrandVoice extends Model
{
    /** @use HasFactory<\Database\Factories\BrandVoiceFactory> */
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'meta_asset_id',
        'tone',
        'style',
        'keywords',
        'avoid_words',
        'compliance_rules',
        'language',
    ];

    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'avoid_words' => 'array',
            'compliance_rules' => 'array',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function metaAsset(): BelongsTo
    {
        return $this->belongsTo(MetaAsset::class);
    }
}
