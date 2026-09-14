<?php

namespace App\Models\Concerns;

use App\Services\VideoUploader;

trait HasVideo
{
    public function videoPlaybackUrl(): ?string
    {
        if (! empty($this->video_path)) {
            return app(VideoUploader::class)->url($this->video_path);
        }

        if (! empty($this->video_url)) {
            return $this->video_url;
        }

        return null;
    }

    public function hasVideo(): bool
    {
        return filled($this->videoPlaybackUrl());
    }
}
