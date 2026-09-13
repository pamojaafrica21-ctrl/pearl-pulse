@props(['experience'])

@php $cover = $experience->coverUrl(); @endphp

<a href="{{ route('experiences.show', $experience) }}" class="destination-card group">
    <div class="overflow-hidden bg-forest/10">
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $experience->name }}" loading="lazy">
        @else
            <div class="aspect-[4/5] bg-gradient-to-br from-forest to-forest-light"></div>
        @endif
    </div>
    <div class="destination-card-meta">
        <p class="text-[11px] tracking-[0.18em] uppercase text-gold">Experience</p>
        <h3 class="font-display text-2xl md:text-3xl text-forest mt-1 group-hover:text-forest-light transition-colors">
            {{ $experience->name }}
        </h3>
        @if($experience->teaser)
            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $experience->teaser }}</p>
        @endif
    </div>
</a>
