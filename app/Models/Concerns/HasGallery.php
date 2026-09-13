<?php

namespace App\Models\Concerns;

use App\Models\ContentImage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasGallery
{
    public function images(): MorphMany
    {
        return $this->morphMany(ContentImage::class, 'imageable')->orderBy('sort_order');
    }
}
