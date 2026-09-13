<?php

namespace App\Livewire\Admin\Support;

use App\Services\ImageUploader;
use Illuminate\Database\Eloquent\Model;

trait DeletesCover
{
    public function deleteRecord(Model $record, ImageUploader $uploader): void
    {
        $uploader->delete($record->cover_path ?? null);

        if (method_exists($record, 'images')) {
            foreach ($record->images as $image) {
                $uploader->delete($image->path);
                $image->delete();
            }
        }

        $record->delete();
    }
}
