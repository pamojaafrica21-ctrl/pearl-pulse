@props(['destination'])

@php
    $cover = $destination->coverUrl();
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
        @if($destination->subtitle)
            <p class="mt-1 text-sm text-forest/70">{{ $destination->subtitle }}</p>
        @endif
        @if($destination->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $destination->teaser }}</p>
        @endif
        @if($destination->duration || $destination->best_time || $destination->price_from)
            <dl class="mt-3 space-y-1 text-xs text-muted">
                @if($destination->duration)
                    <div class="flex gap-2"><dt class="uppercase tracking-wider text-gold/80 shrink-0">Duration</dt><dd>{{ $destination->duration }}</dd></div>
                @endif
                @if($destination->best_time)
                    <div class="flex gap-2"><dt class="uppercase tracking-wider text-gold/80 shrink-0">Best time</dt><dd class="line-clamp-1">{{ $destination->best_time }}</dd></div>
                @endif
                @if($destination->price_from)
                    <div class="flex gap-2"><dt class="uppercase tracking-wider text-gold/80 shrink-0">From</dt><dd>{{ $destination->price_from }}</dd></div>
                @endif
            </dl>
        @endif
    </div>
</a>
