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
<section {{ $attributes->merge(['class' => 'page-cta-stage site-closer__cta relative text-sand']) }}>
    <div class="page-cta-stage__media" aria-hidden="true">
        <img src="{{ $image ?: asset('images/footer-gorilla.jpg') }}" alt="" loading="lazy" decoding="async">
        <div class="page-cta-stage__veil"></div>
    </div>
    <span class="page-cta-stage__frame" aria-hidden="true"></span>
    <div class="relative z-10 mx-auto max-w-2xl px-6 py-24 text-center lg:px-8 lg:py-32">
        <div class="page-cta-stage__ornament" aria-hidden="true"><span></span></div>
        @if($eyebrow)
            <p class="mb-3 text-[13px] uppercase tracking-[0.22em] text-white/80 [text-shadow:0_1px_12px_rgb(0_0_0_/_0.45)]">{{ $eyebrow }}</p>
        @endif
        <h2 class="font-display text-4xl leading-tight text-white md:text-5xl [text-shadow:0_2px_24px_rgb(0_0_0_/_0.45)]">{{ $heading }}</h2>
        @if($text)
            <p class="mx-auto mt-5 max-w-xl text-base leading-relaxed text-white/90 md:text-lg [text-shadow:0_1px_16px_rgb(0_0_0_/_0.4)]">{{ $text }}</p>
        @endif
        <div class="page-cta-stage__actions mt-10 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
            <a href="{{ $href }}" class="btn-outline shadow-[0_8px_30px_rgb(0_0_0_/_0.25)]">{{ $button }}</a>
            @if($secondary && $secondaryLabel)
                <a href="{{ $secondary }}" class="btn-outline shadow-[0_8px_30px_rgb(0_0_0_/_0.25)]">{{ $secondaryLabel }}</a>
            @endif
            {{ $slot }}
        </div>
    </div>
</section>
@endpush
