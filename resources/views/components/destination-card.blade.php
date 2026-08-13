@props(['destination'])

@php
    $uploader = app(\App\Services\ImageUploader::class);
    $cover = $uploader->url($destination->cover_path);
@endphp

<a href="{{ route('destinations.show', $destination) }}" class="destination-card group">
    <div class="overflow-hidden bg-forest/10">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $destination->name }}" loading="lazy">
        @else
            <div class="aspect-[4/5] bg-gradient-to-br from-forest to-forest-light"></div>
        @endif
    </div>
    <div class="destination-card-meta">
        <p class="text-[11px] tracking-[0.18em] uppercase text-gold">
            {{ $destination->country }}@if($destination->region) · {{ $destination->region }}@endif
        </p>
        <h3 class="font-display text-2xl md:text-3xl text-forest mt-1 group-hover:text-forest-light transition-colors">
            {{ $destination->name }}
        </h3>
        @if($destination->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $destination->teaser }}</p>
        @endif
    </div>
</a>
