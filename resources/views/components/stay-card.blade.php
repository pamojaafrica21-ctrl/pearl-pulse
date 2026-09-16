@props(['stay'])

@php $cover = $stay->coverThumbUrl() ?: $stay->coverUrl(); @endphp

<a href="{{ route('stays.show', $stay) }}" class="destination-card group">
    <div class="overflow-hidden bg-forest/10">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $stay->name }}" loading="lazy" decoding="async">
        @else
            <div class="aspect-[4/5] bg-gradient-to-br from-forest to-forest-light"></div>
        @endif
    </div>
    <div class="destination-card-meta">
        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Selected stay</p>
        <h3 class="font-display text-2xl md:text-3xl text-charcoal mt-1 group-hover:text-forest transition-colors">
            {{ $stay->name }}
        </h3>
        @if($stay->location)
            <p class="mt-1 text-sm text-forest/70">{{ $stay->location }}</p>
        @endif
        @if($stay->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $stay->teaser }}</p>
        @endif
    </div>
</a>
