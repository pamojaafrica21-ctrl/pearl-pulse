@extends('layouts.public')

@section('title', $destination->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($destination->seoDescription(), 160))

@section('content')
@php
    $cover = $destination->coverUrl();
    $video = $destination->videoPlaybackUrl();
@endphp
<section class="relative min-h-[70svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$destination->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Destinations', 'href' => route('destinations.index')],
            ['label' => $country->name, 'href' => route('destinations.country', $country)],
            ['label' => $destination->name],
        ]" />
        <p class="text-xs tracking-[0.2em] uppercase text-sand/70 mt-4">
            {{ $country->name }}@if($destination->region) · {{ $destination->region }}@endif
        </p>
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-3">{{ $destination->name }}</h1>
        @if($destination->subtitle)
            <p class="mt-3 text-lg text-sand/80 font-light">{{ $destination->subtitle }}</p>
        @endif
    </div>
</section>

<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-7">
            @if($destination->teaser)
                <p class="font-display text-2xl md:text-3xl text-forest leading-snug">{{ $destination->teaser }}</p>
            @endif
            <div class="prose-safari mt-8">{!! $destination->description !!}</div>
            @if($destination->why)
                <h2 class="font-display text-3xl text-forest mt-12">Why here</h2>
                <div class="prose-safari mt-4">{!! $destination->why !!}</div>
            @endif
        </div>
        <aside class="lg:col-span-5 lg:pl-8">
            <div class="border-t border-sand-deep/40 pt-8">
                <h2 class="text-xs tracking-[0.2em] uppercase text-gold mb-6">At a glance</h2>
                <dl class="space-y-5">
                    @if($destination->duration)
                        <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Suggested stay</dt><dd class="mt-1 text-forest">{{ $destination->duration }}</dd></div>
                    @endif
                    @if($destination->best_time)
                        <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Best time</dt><dd class="mt-1 text-forest">{{ $destination->best_time }}</dd></div>
                    @endif
                    @if($destination->activities)
                        <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Experiences</dt><dd class="mt-1 text-forest">{{ $destination->activities }}</dd></div>
                    @endif
                    @foreach($destination->highlights ?? [] as $item)
                        @if(!empty($item['label']) || !empty($item['value']))
                            <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">{{ $item['label'] ?? '' }}</dt><dd class="mt-1 text-forest">{{ $item['value'] ?? '' }}</dd></div>
                        @endif
                    @endforeach
                </dl>
            </div>
        </aside>
    </div>
</section>

@if($destination->experiences->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Experiences</h2>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($destination->experiences as $experience)
                <x-experience-card :experience="$experience" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($destination->journeys->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Journeys here</h2>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($destination->journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif

@php
    $stays = $destination->stays->merge($destination->primaryStays)->unique('id');
@endphp
@if($stays->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-3">Selected stays</h2>
        <p class="text-muted mb-8">We do not own these properties.</p>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($stays as $stay)
                <x-stay-card :stay="$stay" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($destination->practical)
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-3xl text-forest mb-4">Practical information</h2>
        <div class="prose-safari">{!! $destination->practical !!}</div>
    </div>
</section>
@endif

@if($destination->images->isNotEmpty())
<section class="bg-cream pb-16" x-data="{ active: 0 }">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Gallery</h2>
        <div class="relative overflow-hidden bg-forest/5 aspect-[16/10]">
            @foreach($destination->images as $index => $image)
                <img src="{{ $image->url() }}" alt="{{ $image->alt ?: $destination->name }}" class="absolute inset-0 h-full w-full object-cover" x-show="active === {{ $index }}" x-transition.opacity>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta
    heading="Ready to experience {{ $destination->name }}?"
    text="Explore journeys that include this place — or ask us to design one."
    button="Explore journeys"
    :href="route('journeys.country', $country)"
/>
@endsection
