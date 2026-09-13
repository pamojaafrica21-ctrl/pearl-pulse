<?php

namespace App\Models\Concerns;

use App\Services\ImageUploader;

trait HasCover
{
    public function coverUrl(): ?string
    {
        return app(ImageUploader::class)->url($this->cover_path);
    }

    public function coverThumbUrl(): ?string
    {
        return app(ImageUploader::class)->thumbUrl($this->cover_path);
    }
}
