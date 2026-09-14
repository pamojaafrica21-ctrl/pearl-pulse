<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class VideoUploader
{
    public function __construct(
        protected string $disk = ''
    ) {
        $this->disk = $disk ?: config('filesystems.uploads', 'public');
    }

    public function store(UploadedFile $file, string $directory = 'videos'): string
    {
        $directory = trim($directory, '/');
        $extension = $this->normalizeExtension($file);
        $basename = Str::uuid()->toString();
        $relative = "{$directory}/{$basename}.{$extension}";

        $stored = Storage::disk($this->disk)->putFileAs($directory, $file, "{$basename}.{$extension}");

        if (! $stored) {
            throw new RuntimeException('Unable to store uploaded video.');
        }

        return $relative;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk($this->disk)->delete($path);
    }

    public function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if ($this->disk === 'public' || config('filesystems.disks.'.$this->disk.'.driver') === 'local') {
            return '/storage/'.ltrim($path, '/');
        }

        return Storage::disk($this->disk)->url($path);
    }

    protected function normalizeExtension(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'mp4');

        return in_array($ext, ['mp4', 'webm', 'mov', 'm4v'], true) ? $ext : 'mp4';
    }
}
