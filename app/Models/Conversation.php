<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['meta_asset_id', 'ig_conversation_id', 'archived', 'unread_count'];

    protected function casts(): array
    {
        return ['archived' => 'boolean'];
    }

    public function metaAsset(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MetaAsset::class);
    }

    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InboxMessage::class, 'conversation_id');
    }
}
