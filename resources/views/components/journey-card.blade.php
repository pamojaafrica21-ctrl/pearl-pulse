@props([
    'journey',
    'variant' => 'default',
])

@php
    $cover = $journey->coverThumbUrl() ?: $journey->coverUrl();
    $countries = $journey->relationLoaded('countries')
        ? $journey->countries->pluck('name')->join(' · ')
        : '';
    $isSignature = $variant === 'signature';
    $kicker = $journey->duration_label
        ?: ($countries ? \Illuminate\Support\Str::before($countries, ' · ') : 'Safari');
@endphp

@if($isSignature)
    <a
        href="{{ route('journeys.show', $journey) }}"
        {{ $attributes->class(['journey-card journey-card--signature group']) }}
        data-card-scroller-item
    >
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $journey->name }}" loading="lazy" decoding="async">
        @else
            <div class="journey-card__fallback" aria-hidden="true"></div>
        @endif
        <div class="journey-card__shade" aria-hidden="true"></div>
        <div class="journey-card__overlay">
            <p class="journey-card__country">{{ $kicker }}</p>
            <h3 class="journey-card__title">{{ $journey->name }}</h3>
            @if($journey->teaser)
                <p class="journey-card__teaser">{{ $journey->teaser }}</p>
            @endif
            <span class="journey-card__cta">Explore now</span>
        </div>
    </a>
@else
    <a href="{{ route('journeys.show', $journey) }}" {{ $attributes->class(['destination-card group']) }}>
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
@endif
