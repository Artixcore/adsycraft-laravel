<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InboxMessage extends Model
{
    protected $table = 'inbox_messages';

    protected $fillable = ['conversation_id', 'from_id', 'to_id', 'text', 'direction', 'meta_message_id'];

    public function conversation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
}
