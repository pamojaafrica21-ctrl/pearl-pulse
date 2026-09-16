@props([
    'record' => null,
    'uploader' => null,
])

<div class="space-y-3">
    <label class="block text-xs tracking-[0.14em] uppercase text-muted">Hero video</label>
    <p class="text-xs text-muted">Upload an MP4/WebM (max ~100MB), or paste a YouTube / direct video URL. Uploaded file takes priority.</p>

    @if($record?->video_path && ! $removeVideoFile)
        <div class="flex flex-wrap items-center gap-3 text-sm">
            <span class="inline-flex items-center gap-2 rounded-sm bg-forest/10 px-2 py-1 text-forest">
                Video file attached
            </span>
            <a href="{{ $uploader?->url($record->video_path) }}" target="_blank" rel="noopener" class="text-forest hover:underline">Preview</a>
            <button type="button" wire:click="clearUploadedVideo" class="text-red-700 hover:underline">Remove file</button>
        </div>
    @elseif($removeVideoFile)
        <p class="text-xs text-amber-800">Video file will be removed when you save.</p>
    @endif

    <input type="file" wire:model="video" accept="video/mp4,video/webm,video/quicktime" class="text-sm w-full">
    <div wire:loading wire:target="video" class="text-xs text-muted">Uploading video…</div>
    @error('video') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

    <div>
        <label class="block text-xs tracking-[0.12em] uppercase text-muted mb-1">Or video URL</label>
        <input type="url" wire:model="video_url" placeholder="https://…" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        @error('video_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>
