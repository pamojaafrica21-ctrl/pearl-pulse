@extends('layouts.public')

@section('title', 'Specialist journeys | Pearl Pulse Safaris')
@section('meta_description', 'Family, honeymoon, photography, and wellness journeys designed privately across East Africa.')

@section('content')
<section class="surface surface--beige pt-16 pb-12 lg:pt-20">
    <div class="reveal mx-auto max-w-3xl px-5 lg:px-8 text-center">
        <p class="section-eyebrow">Specialist journeys</p>
        <h1 class="font-display text-4xl md:text-5xl text-charcoal">Travel shaped around how you want to feel</h1>
        <p class="mt-4 text-muted leading-relaxed">Family, honeymoon, photography, and wellness — each a private chapter, not a catalogue package.</p>
    </div>
</section>

<section class="surface surface--beige pb-16 lg:pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-6 md:grid-cols-2">
        @foreach($specialists as $specialist)
            <a href="{{ route('specialist.show', $specialist) }}" class="specialist-card group reveal">
                <div class="specialist-card__media">
                    @if($specialist->coverUrl())
                        <img src="{{ $specialist->coverThumbUrl() ?: $specialist->coverUrl() }}" alt="{{ $specialist->name }}" loading="lazy" decoding="async">
                    @endif
                </div>
                <div class="specialist-card__copy">
                    @if($specialist->subtitle)
                        <p class="text-[13px] tracking-[0.18em] uppercase text-white/75">{{ $specialist->subtitle }}</p>
                    @endif
                    <h2 class="font-display text-3xl md:text-4xl text-white mt-2">{{ $specialist->name }}</h2>
                    @if($specialist->teaser)
                        <p class="mt-3 text-sm text-white/85 leading-relaxed">{{ $specialist->teaser }}</p>
                    @endif
                    <span class="mt-5 inline-flex text-[13px] tracking-[0.14em] uppercase text-white">Explore</span>
                </div>
            </a>
        @endforeach
    </div>
</section>

<section class="surface surface--white py-14 lg:py-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center reveal">
        <p class="text-muted leading-relaxed">Not sure which specialist path fits? Write to us — we will listen first.</p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-outline-dark">Speak to a specialist</a>
            <a href="{{ route('plan') }}" class="btn-primary">Plan your journey</a>
        </div>
    </div>
</section>
@endsection
