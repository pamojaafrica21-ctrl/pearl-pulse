@props(['country'])

@php $cover = $country->coverUrl(); @endphp

<a href="{{ route('destinations.country', $country) }}" class="destination-card group">
    <div class="overflow-hidden bg-forest/10">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $country->name }}" loading="lazy">
        @else
            <div class="aspect-[4/5] bg-gradient-to-br from-forest to-forest-light"></div>
        @endif
    </div>
    <div class="destination-card-meta">
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Destination</p>
        <h3 class="font-display text-2xl md:text-3xl text-charcoal mt-1 group-hover:text-forest transition-colors">
            {{ $country->name }}
        </h3>
        @if($country->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $country->teaser }}</p>
        @endif
    </div>
</a>
