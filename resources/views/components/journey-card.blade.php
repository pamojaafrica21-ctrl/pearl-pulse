@props(['journey'])

@php
    $cover = $journey->coverThumbUrl() ?: $journey->coverUrl();
    $countries = $journey->relationLoaded('countries')
        ? $journey->countries->pluck('name')->join(' · ')
        : '';
@endphp

<a href="{{ route('journeys.show', $journey) }}" class="destination-card group">
    <div class="overflow-hidden bg-forest/10">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $journey->name }}" loading="lazy" decoding="async">
        @else
            <div class="aspect-[4/5] bg-gradient-to-br from-forest to-forest-light"></div>
        @endif
    </div>
    <div class="destination-card-meta">
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">
            {{ $countries ?: 'East Africa' }}
            @if($journey->duration_label) · {{ $journey->duration_label }}@endif
        </p>
        <h3 class="font-display text-2xl md:text-3xl text-charcoal mt-1 group-hover:text-forest transition-colors">
            {{ $journey->name }}
        </h3>
        @if($journey->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-3">{{ $journey->teaser }}</p>
        @endif
        <p class="mt-3 text-[11px] tracking-[0.14em] uppercase text-forest/70">Private proposal</p>
    </div>
</a>
