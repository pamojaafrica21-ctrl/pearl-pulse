@extends('layouts.public')

@section('title', $experience->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($experience->seoDescription(), 160))

@section('content')
@php $cover = $experience->coverUrl(); @endphp
<section class="relative min-h-[68svh] flex items-end overflow-hidden bg-forest">
    @if($cover)
        <img src="{{ $cover }}" alt="{{ $experience->name }}" class="absolute inset-0 h-full w-full object-cover scale-105" data-parallax="0.1">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Experiences', 'href' => route('experiences.index')],
            ['label' => $experience->name],
        ]" />
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-sand mt-4 fade-up leading-[0.95]">{{ $experience->name }}</h1>
        @if($experience->subtitle)
            <p class="mt-4 text-lg md:text-xl text-sand/80 fade-up max-w-2xl" style="animation-delay: 0.1s">{{ $experience->subtitle }}</p>
        @endif
    </div>
</section>

<section class="surface surface--white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 split-editorial reveal">
        <div>
            @if($experience->teaser)
                <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">{{ $experience->teaser }}</p>
            @endif
            <div class="prose-safari mt-8">{!! $experience->description !!}</div>
        </div>
        <div class="split-editorial__still">
            @if($cover)
                <img src="{{ $cover }}" alt="" loading="lazy" decoding="async">
            @endif
        </div>
    </div>
</section>

@if($experience->destinations->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Where</h2>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3 reveal-stagger">
            @foreach($experience->destinations as $destination)
                <a href="{{ $destination->publicUrl() }}" class="stage-link min-h-[16rem]">
                    @if($destination->coverUrl())
                        <img src="{{ $destination->coverThumbUrl() ?: $destination->coverUrl() }}" alt="{{ $destination->name }}" loading="lazy" decoding="async">
                    @endif
                    <div class="stage-link__shade"></div>
                    <div class="stage-link__copy">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-white/70">{{ $destination->country?->name }}</p>
                        <h3 class="font-display text-3xl text-white mt-1">{{ $destination->name }}</h3>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($experience->journeys->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Journeys</h2>
        <div class="filmstrip reveal-stagger">
            @foreach($experience->journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta
    heading="Ready for {{ $experience->name }}?"
    text="Explore journeys built around this experience — or ask us to design one around you."
    button="Explore journeys"
    :href="route('journeys.index')"
    :secondary="route('plan')"
    secondaryLabel="Plan your journey"
/>
@endsection
