<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdLibraryCollectionItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ad_library_collection_id',
        'ad_archive_id',
        'snapshot_url',
        'page_name',
        'ad_creative_body',
        'page_id',
        'publisher_platforms',
        'ad_delivery_start_time',
    ];

    protected function casts(): array
    {
        return [
            'publisher_platforms' => 'array',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(AdLibraryCollection::class, 'ad_library_collection_id');
    }
}
