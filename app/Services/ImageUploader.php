<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageUploader
{
    public function __construct(
        protected string $disk = ''
    ) {
        $this->disk = $disk ?: config('filesystems.uploads', 'public');
    }

    /**
     * Store an uploaded image and create a resized web version (max 1600px)
     * plus a thumb (max 600px). Returns the primary (web) relative path.
     */
    public function store(UploadedFile $file, string $directory = 'uploads'): string
    {
        $directory = trim($directory, '/');
        $basename = Str::uuid()->toString();
        $extension = $this->normalizeExtension($file);

        $original = $file->getRealPath();
        if ($original === false) {
            throw new RuntimeException('Unable to read uploaded file.');
        }

        $webRelative = "{$directory}/{$basename}.{$extension}";
        $thumbRelative = "{$directory}/{$basename}_thumb.{$extension}";

        $webBinary = $this->resizeToMax($original, 1600, $extension);
        $thumbBinary = $this->resizeToMax($original, 600, $extension);

        Storage::disk($this->disk)->put($webRelative, $webBinary);
        Storage::disk($this->disk)->put($thumbRelative, $thumbBinary);

        return $webRelative;
    }

    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = Storage::disk($this->disk);
        $disk->delete($path);

        $thumb = preg_replace('/(\.[a-zA-Z0-9]+)$/', '_thumb$1', $path);
        if ($thumb && $thumb !== $path) {
            $disk->delete($thumb);
        }
    }

    public function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // Root-relative URLs work on both localhost and 127.0.0.1
        if ($this->disk === 'public' || config('filesystems.disks.'.$this->disk.'.driver') === 'local') {
            return '/storage/'.ltrim($path, '/');
        }

        return Storage::disk($this->disk)->url($path);
    }

    public function thumbUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $thumb = preg_replace('/(\.[a-zA-Z0-9]+)$/', '_thumb$1', $path);

        if ($thumb && Storage::disk($this->disk)->exists($thumb)) {
            if ($this->disk === 'public' || config('filesystems.disks.'.$this->disk.'.driver') === 'local') {
                return '/storage/'.ltrim($thumb, '/');
            }

            return Storage::disk($this->disk)->url($thumb);
        }

        return $this->url($path);
    }

    /**
     * Store an existing local image file (e.g. for seeders) with web + thumb variants.
     */
    public function storeFromPath(string $sourcePath, string $directory = 'uploads', string $extension = 'jpg'): string
    {
        $directory = trim($directory, '/');
        $basename = Str::uuid()->toString();
        $webRelative = "{$directory}/{$basename}.{$extension}";
        $thumbRelative = "{$directory}/{$basename}_thumb.{$extension}";

        $webBinary = $this->resizeToMax($sourcePath, 1600, $extension);
        $thumbBinary = $this->resizeToMax($sourcePath, 600, $extension);

        Storage::disk($this->disk)->put($webRelative, $webBinary);
        Storage::disk($this->disk)->put($thumbRelative, $thumbBinary);

        return $webRelative;
    }

    protected function normalizeExtension(UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? ($ext === 'jpeg' ? 'jpg' : $ext) : 'jpg';
    }

    protected function resizeToMax(string $sourcePath, int $maxEdge, string $extension): string
    {
        if (! extension_loaded('gd')) {
            return (string) file_get_contents($sourcePath);
        }

        [$width, $height, $type] = getimagesize($sourcePath);
        $source = $this->createImageResource($sourcePath, $type);

        if ($source === false) {
            return (string) file_get_contents($sourcePath);
        }

        $scale = min(1, $maxEdge / max($width, $height));
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newWidth, $newHeight);

        if (in_array($extension, ['png', 'webp', 'gif'], true)) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        match ($extension) {
            'png' => imagepng($canvas, null, 7),
            'webp' => function_exists('imagewebp') ? imagewebp($canvas, null, 82) : imagejpeg($canvas, null, 82),
            'gif' => imagegif($canvas),
            default => imagejpeg($canvas, null, 82),
        };
        $binary = (string) ob_get_clean();

        imagedestroy($source);
        imagedestroy($canvas);

        return $binary;
    }

    /**
     * @return \GdImage|false
     */
    protected function createImageResource(string $path, int $type)
    {
        return match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false,
            IMAGETYPE_GIF => imagecreatefromgif($path),
            default => false,
        };
    }
}
