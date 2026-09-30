@extends('layouts.public')

@section('title', $stay->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($stay->seoDescription(), 160))

@section('content')
@php
    $cover = $stay->coverUrl();
    $video = $stay->videoPlaybackUrl();
@endphp
<section class="relative min-h-[60svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$stay->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <p class="text-xs tracking-[0.2em] uppercase text-sand/70">Preferred stay</p>
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-3">{{ $stay->name }}</h1>
        <div class="mt-4 flex flex-wrap gap-3 text-sm text-sand/80">
            @if($stay->location)
                <span>{{ $stay->location }}</span>
            @endif
            @if($stay->style)
                <span class="opacity-50" aria-hidden="true">·</span>
                <span>{{ $stay->style }}</span>
            @endif
        </div>
    </div>
</section>

<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        @if($stay->teaser)
            <p class="text-lg text-muted leading-relaxed mb-8">{{ $stay->teaser }}</p>
        @endif
        <div class="prose-safari">{!! $stay->description !!}</div>
        @if($stay->destination)
            <p class="mt-8 text-sm text-muted">Near <a href="{{ $stay->destination->publicUrl() }}" class="text-charcoal underline hover:text-forest transition">{{ $stay->destination->name }}</a></p>
        @endif
    </div>
</section>

@if($stay->images->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Gallery</h2>
        <div class="gallery-mosaic reveal-stagger">
            @foreach($stay->images as $image)
                <img src="{{ $image->url() }}" alt="{{ $image->alt ?: $stay->name }}" loading="lazy" decoding="async">
            @endforeach
        </div>
    </div>
</section>
@endif

@if($stay->journeys->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Journeys that may include this stay</h2>
        <div class="grid gap-8 sm:grid-cols-3">
            @foreach($stay->journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta heading="Ask us about this stay" text="Availability and fit depend on your dates and the rest of the journey." />
@endsection
