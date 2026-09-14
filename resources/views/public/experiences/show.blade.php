@extends('layouts.public')

@section('title', $experience->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($experience->seoDescription(), 160))

@section('content')
@php
    $cover = $experience->coverUrl();
    $video = $experience->videoPlaybackUrl();
@endphp
<section class="relative min-h-[60svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$experience->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Experiences', 'href' => route('experiences.index')],
            ['label' => $experience->name],
        ]" />
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-4">{{ $experience->name }}</h1>
        @if($experience->subtitle)
            <p class="mt-3 text-lg text-sand/80">{{ $experience->subtitle }}</p>
        @endif
    </div>
</section>
<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        @if($experience->teaser)
            <p class="font-display text-2xl md:text-3xl text-forest leading-snug">{{ $experience->teaser }}</p>
        @endif
        <div class="prose-safari mt-8">{!! $experience->description !!}</div>
    </div>
</section>
@if($experience->destinations->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Where</h2>
        <div class="grid gap-8 sm:grid-cols-3">
            @foreach($experience->destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>
    </div>
</section>
@endif
@if($experience->journeys->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Journeys</h2>
        <div class="grid gap-8 sm:grid-cols-3">
            @foreach($experience->journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif
<x-page-cta heading="Ready for {{ $experience->name }}?" button="Explore journeys" :href="route('journeys.index')" />
@endsection
