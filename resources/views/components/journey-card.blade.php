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
            <span class="journey-card__cta">Explore journey</span>
        </div>
    </a>
@else
    <a href="{{ route('journeys.show', $journey) }}" {{ $attributes->class(['journey-card journey-card--listing group']) }}>
        <div class="journey-card__media">
            @if($cover)
                <img src="{{ $cover }}" alt="{{ $journey->name }}" loading="lazy" decoding="async">
            @else
                <div class="journey-card__fallback" aria-hidden="true"></div>
            @endif
            <div class="journey-card__shade" aria-hidden="true"></div>
        </div>
        <div class="journey-card__body">
            <p class="journey-card__meta">
                {{ $countries ?: 'East Africa' }}
                @if($journey->duration_label)
                    <span aria-hidden="true"> · </span>{{ $journey->duration_label }}
                @endif
            </p>
            <h3 class="journey-card__title">{{ $journey->name }}</h3>
            @if($journey->teaser)
                <p class="journey-card__teaser">{{ $journey->teaser }}</p>
            @endif
            <div class="journey-card__footer">
                <span class="journey-card__price">{{ $journey->priceLabel() }}</span>
                <span class="journey-card__cta">Explore journey</span>
            </div>
        </div>
    </a>
@endif
