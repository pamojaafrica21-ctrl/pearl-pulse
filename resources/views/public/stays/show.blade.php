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
        <p class="text-xs tracking-[0.2em] uppercase text-sand/70">Selected stay</p>
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-3">{{ $stay->name }}</h1>
        @if($stay->location)
            <p class="mt-3 text-sand/80">{{ $stay->location }}</p>
        @endif
    </div>
</section>
<section class="bg-cream py-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <div class="prose-safari">{!! $stay->description !!}</div>
        @if($stay->destination)
            <p class="mt-8 text-sm text-muted">Near <a href="{{ $stay->destination->publicUrl() }}" class="text-forest underline">{{ $stay->destination->name }}</a></p>
        @endif
    </div>
</section>
@if($stay->journeys->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Journeys that may include this stay</h2>
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
