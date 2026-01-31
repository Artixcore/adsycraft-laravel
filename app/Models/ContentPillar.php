<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentPillar extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_account_id',
        'name',
        'description',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'integer',
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
