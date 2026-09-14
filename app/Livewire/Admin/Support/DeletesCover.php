<?php

namespace App\Livewire\Admin\Support;

use App\Services\ImageUploader;
use App\Services\VideoUploader;
use Illuminate\Database\Eloquent\Model;

trait DeletesCover
{
    public function deleteRecord(Model $record, ImageUploader $uploader): void
    {
        $uploader->delete($record->cover_path ?? null);

        if (! empty($record->video_path)) {
            app(VideoUploader::class)->delete($record->video_path);
        }

        if (method_exists($record, 'images')) {
            foreach ($record->images as $image) {
                $uploader->delete($image->path);
                $image->delete();
            }
        }

        $record->delete();
    }
}
