@extends('layouts.public')

@section('title', 'Travel with a reason | Pearl Pulse Safaris')
@section('meta_description', 'How Pearl Pulse supports conservation and communities across East Africa — permits, camps, partners, and honest choices.')

@section('content')
<section class="relative min-h-[55svh] flex items-end overflow-hidden bg-forest">
    @if(!empty($heroImage))
        <img src="{{ $heroImage }}" alt="" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-charcoal/90 via-charcoal/40 to-black/25"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 lg:px-8 pb-14 pt-28 reveal">
        <p class="section-eyebrow text-white/75">{{ $eyebrow }}</p>
        <h1 class="font-display text-5xl md:text-6xl text-white mt-3 leading-tight max-w-3xl">{{ $title }}</h1>
        @if($lead)
            <p class="mt-5 max-w-2xl text-lg text-white/90 leading-relaxed">{{ $lead }}</p>
        @endif
    </div>
</section>

@if($intro)
<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <div class="prose-safari">
            @foreach(preg_split('/\n\s*\n/', trim($intro)) as $paragraph)
                @if(trim($paragraph) !== '')
                    <p>{{ trim($paragraph) }}</p>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($pillars))
<section class="surface surface--beige py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-2xl mb-12">
            <p class="section-eyebrow">How we travel</p>
            <h2 class="font-display text-3xl md:text-4xl text-charcoal">Choices that leave something behind</h2>
        </div>
        <div class="grid gap-8 md:grid-cols-2">
            @foreach($pillars as $i => $pillar)
                <div class="reveal border-t border-charcoal/10 pt-6">
                    <span class="font-display text-2xl text-forest/60">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="font-display text-2xl text-charcoal mt-3">{{ $pillar['title'] }}</h3>
                    <p class="mt-3 text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($partners))
<section class="surface surface--white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-2xl mb-12">
            <p class="section-eyebrow">Partners & projects</p>
            <h2 class="font-display text-3xl md:text-4xl text-charcoal">Who we work alongside</h2>
            <p class="mt-4 text-muted leading-relaxed">Illustrative partnerships — names and details can be refined in admin as relationships deepen.</p>
        </div>
        <div class="grid gap-10 sm:grid-cols-2">
            @foreach($partners as $partner)
                <article class="reveal">
                    <h3 class="font-display text-2xl text-charcoal">{{ $partner['title'] }}</h3>
                    <p class="mt-3 text-muted leading-relaxed">{{ $partner['text'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta
    :heading="$ctaHeading ?: 'Travel with intention'"
    :text="$ctaText ?: 'Tell us what you hope your journey will leave behind — we will shape the proposal around it.'"
    button="Speak to a specialist"
    :href="route('contact')"
    :secondary="route('plan')"
    secondary-label="Plan your journey"
/>
@endsection
