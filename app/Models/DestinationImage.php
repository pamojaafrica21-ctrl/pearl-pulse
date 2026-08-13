<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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
        return Storage::disk(config('filesystems.uploads', 'public'))->url($this->path);
    }
}
