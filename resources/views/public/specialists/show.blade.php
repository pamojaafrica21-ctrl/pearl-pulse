@extends('layouts.public')

@section('title', ($specialist->meta_title ?: $specialist->name.' | Pearl Pulse Safaris'))
@section('meta_description', $specialist->meta_description ?: $specialist->teaser)

@section('content')
@php
    $cover = $specialist->coverUrl();
@endphp
<section class="relative min-h-[60svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :image="$cover" :alt="$specialist->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/35 to-black/20"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 lg:px-8 pb-14 pt-28">
        @if($specialist->subtitle)
            <p class="text-[13px] tracking-[0.22em] uppercase text-white/75">{{ $specialist->subtitle }}</p>
        @endif
        <h1 class="font-display text-5xl md:text-6xl text-white mt-3 leading-tight">{{ $specialist->name }}</h1>
        @if($specialist->teaser)
            <p class="mt-5 max-w-2xl text-lg text-white/90 leading-relaxed">{{ $specialist->teaser }}</p>
        @endif
    </div>
</section>

<section class="surface surface--white py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <div class="prose-safari">
            {!! $specialist->description !!}
        </div>
        <div class="mt-12 flex flex-wrap gap-4">
            <a href="{{ route('contact') }}" class="btn-primary">Speak to a specialist</a>
            <a href="{{ route('plan') }}" class="btn-outline-dark">Plan your journey</a>
            <a href="{{ route('specialist.index') }}" class="text-sm text-muted hover:text-forest transition self-center">All specialist journeys</a>
        </div>
    </div>
</section>

@if(($relatedJourneys ?? collect())->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow reveal">Starting points</p>
        <h2 class="font-display text-3xl md:text-4xl text-charcoal reveal">Journeys you might begin from</h2>
        <div class="mt-8 grid gap-8 sm:grid-cols-3">
            @foreach($relatedJourneys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($others->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow reveal">Also consider</p>
        <h2 class="font-display text-3xl md:text-4xl text-charcoal reveal">Other specialist journeys</h2>
        <div class="mt-8 grid gap-5 md:grid-cols-3">
            @foreach($others as $other)
                <a href="{{ route('specialist.show', $other) }}" class="group reveal">
                    <div class="aspect-[4/5] overflow-hidden bg-forest relative">
                        @if($other->coverUrl())
                            <img src="{{ $other->coverThumbUrl() ?: $other->coverUrl() }}" alt="{{ $other->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal/80 via-transparent to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-5">
                            <h3 class="font-display text-2xl text-white">{{ $other->name }}</h3>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta
    heading="Ready to shape this journey?"
    text="Tell us who is travelling and how you want the days to feel."
    button="Speak to a specialist"
    :href="route('contact')"
    :secondary="route('plan')"
    secondary-label="Plan your journey"
/>
@endsection
