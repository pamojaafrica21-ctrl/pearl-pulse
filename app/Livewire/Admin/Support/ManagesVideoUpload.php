<?php

namespace App\Livewire\Admin\Support;

use App\Services\VideoUploader;
use Illuminate\Database\Eloquent\Model;

trait ManagesVideoUpload
{
    public $video = null;

    public string $video_url = '';

    public bool $removeVideoFile = false;

    protected function videoValidationRules(): array
    {
        return [
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'removeVideoFile' => ['boolean'],
        ];
    }

    protected function loadVideoState(Model $record): void
    {
        $this->video_url = (string) ($record->video_url ?? '');
        $this->removeVideoFile = false;
        $this->video = null;
    }

    protected function persistVideo(Model $record, VideoUploader $uploader, string $directory): void
    {
        $updates = [
            'video_url' => $this->video_url !== '' ? $this->video_url : null,
        ];

        if ($this->removeVideoFile && $record->video_path) {
            $uploader->delete($record->video_path);
            $updates['video_path'] = null;
            $this->removeVideoFile = false;
        }

        if ($this->video) {
            $uploader->delete($record->video_path);
            $updates['video_path'] = $uploader->store($this->video, trim($directory, '/'));
            $this->video = null;
        }

        $record->update($updates);
    }

    public function clearUploadedVideo(VideoUploader $uploader): void
    {
        // Marks existing stored file for removal on save; also clears pending upload.
        $this->video = null;
        $this->removeVideoFile = true;
    }
}
