@props([
    'eyebrow' => '',
    'heading',
    'text' => '',
    'button' => 'Plan your journey',
    'href' => null,
    'secondary' => null,
    'secondaryLabel' => null,
    'image' => null,
])

@php
    $href = $href ?: route('plan');
@endphp

@push('before-footer')
<section {{ $attributes->merge(['class' => 'site-closer__cta relative text-sand pt-20 pb-10 lg:pt-28 lg:pb-12']) }}>
    <div class="relative z-10 mx-auto max-w-2xl px-5 lg:px-8 text-center">
        @if($eyebrow)
            <p class="text-[11px] tracking-[0.22em] uppercase text-white/80 mb-3 [text-shadow:0_1px_12px_rgb(0_0_0_/_0.45)]">{{ $eyebrow }}</p>
        @endif
        <h2 class="font-display text-4xl md:text-5xl text-white leading-tight [text-shadow:0_2px_24px_rgb(0_0_0_/_0.45)]">{{ $heading }}</h2>
        @if($text)
            <p class="mt-5 text-base md:text-lg text-white/90 leading-relaxed [text-shadow:0_1px_16px_rgb(0_0_0_/_0.4)]">{{ $text }}</p>
        @endif
        <div class="mt-10 flex flex-wrap items-center justify-center gap-5">
            <a href="{{ $href }}" class="btn-outline shadow-[0_8px_30px_rgb(0_0_0_/_0.25)]">{{ $button }}</a>
            @if($secondary && $secondaryLabel)
                <a href="{{ $secondary }}" class="text-sm tracking-[0.14em] uppercase text-white/85 hover:text-white transition [text-shadow:0_1px_12px_rgb(0_0_0_/_0.4)]">{{ $secondaryLabel }}</a>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
@endpush
