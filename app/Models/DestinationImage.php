<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DestinationImage extends Model
{
    protected $fillable = [
        'destination_id',
        'path',
        'alt',
        'sort_order',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function url(): string
    {
        return app(\App\Services\ImageUploader::class)->url($this->path) ?? '';
    }
}
