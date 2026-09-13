<?php

namespace App\Models;

use App\Services\ImageUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContentImage extends Model
{
    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'path',
        'alt',
        'sort_order',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function url(): ?string
    {
        return app(ImageUploader::class)->url($this->path);
    }
}
