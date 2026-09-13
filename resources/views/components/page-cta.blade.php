@props([
    'eyebrow' => '',
    'heading',
    'text' => '',
    'button' => 'Plan your journey',
    'href' => null,
    'secondary' => null,
    'secondaryLabel' => null,
])

@php
    $href = $href ?: route('plan');
@endphp

<section {{ $attributes->merge(['class' => 'bg-forest text-sand py-16 lg:py-24']) }}>
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center">
        @if($eyebrow)
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $eyebrow }}</p>
        @endif
        <h2 class="font-display text-4xl md:text-5xl">{{ $heading }}</h2>
        @if($text)
            <p class="mt-4 text-sand/75 leading-relaxed">{{ $text }}</p>
        @endif
        <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ $href }}" class="btn-outline">{{ $button }}</a>
            @if($secondary && $secondaryLabel)
                <a href="{{ $secondary }}" class="text-sm tracking-[0.14em] uppercase text-sand/70 hover:text-sand">{{ $secondaryLabel }}</a>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
