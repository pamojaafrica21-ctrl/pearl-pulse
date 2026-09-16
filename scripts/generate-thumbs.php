<?php

use App\Services\ImageUploader;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$uploader = app(ImageUploader::class);
$disk = Storage::disk(config('filesystems.uploads', 'public'));
$created = 0;
$skipped = 0;

foreach ($disk->allFiles() as $path) {
    if (! preg_match('/\.(jpe?g|png|webp|gif)$/i', $path) || str_contains($path, '_thumb.')) {
        continue;
    }

    $thumb = preg_replace('/(\.[a-zA-Z0-9]+)$/', '_thumb$1', $path);
    $already = $thumb && $disk->exists($thumb);
    $result = $uploader->ensureThumb($path);

    if (! $already && $result && $disk->exists($result)) {
        $created++;
        echo "thumb: {$path}\n";
    } else {
        $skipped++;
    }
}

echo "created={$created} already_ok={$skipped}\n";
