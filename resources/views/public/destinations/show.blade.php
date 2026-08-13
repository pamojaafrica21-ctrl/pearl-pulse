@extends('layouts.public')

@section('title', $destination->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($destination->seoDescription(), 160))

@section('content')
@php
    $cover = $destination->coverUrl();
@endphp

<section class="relative min-h-[70svh] flex items-end overflow-hidden bg-forest">
    @if($cover)
        <img src="{{ $cover }}" alt="{{ $destination->name }}" class="absolute inset-0 h-full w-full object-cover fade-in">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-36 lg:px-8">
        <p class="text-xs tracking-[0.2em] uppercase text-sand/70 fade-up">
            {{ $destination->country }}@if($destination->region) · {{ $destination->region }}@endif
        </p>
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-3 fade-up" style="animation-delay:0.1s">{{ $destination->name }}</h1>
        @if($destination->subtitle)
            <p class="mt-3 text-lg text-sand/80 font-light fade-up" style="animation-delay:0.15s">{{ $destination->subtitle }}</p>
        @endif
    </div>
</section>

<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-7">
            @if($destination->teaser)
                <p class="font-display text-2xl md:text-3xl text-forest leading-snug">{{ $destination->teaser }}</p>
            @endif
            <div class="prose-safari mt-8">
                {!! $destination->description !!}
            </div>
        </div>

        <aside class="lg:col-span-5 lg:pl-8">
            <div class="border-t border-sand-deep/40 pt-8">
                <h2 class="text-xs tracking-[0.2em] uppercase text-gold mb-6">At a glance</h2>
                <dl class="space-y-5">
                    @if($destination->duration)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Duration</dt>
                            <dd class="mt-1 text-forest">{{ $destination->duration }}</dd>
                        </div>
                    @endif
                    @if($destination->best_time)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Best time to visit</dt>
                            <dd class="mt-1 text-forest">{{ $destination->best_time }}</dd>
                        </div>
                    @endif
                    @if($destination->activities)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Activities</dt>
                            <dd class="mt-1 text-forest">{{ $destination->activities }}</dd>
                        </div>
                    @endif
                    @if($destination->price_from)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">From</dt>
                            <dd class="mt-1 text-forest">{{ $destination->price_from }}</dd>
                        </div>
                    @endif
                    @foreach($destination->highlights ?? [] as $item)
                        @if(!empty($item['label']) || !empty($item['value']))
                            <div>
                                <dt class="text-xs tracking-[0.15em] uppercase text-muted">{{ $item['label'] ?? '' }}</dt>
                                <dd class="mt-1 text-forest">{{ $item['value'] ?? '' }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>
            </div>
        </aside>
    </div>
</section>

@if($destination->images->isNotEmpty())
<section class="bg-cream pb-16 lg:pb-24" x-data="{ active: 0 }">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Gallery</h2>
        <div class="relative overflow-hidden bg-forest/5 aspect-[16/10]">
            @foreach($destination->images as $index => $image)
                <img
                    src="{{ $image->url() }}"
                    alt="{{ $image->alt ?: $destination->name }}"
                    class="absolute inset-0 h-full w-full object-cover transition-opacity duration-500"
                    x-show="active === {{ $index }}"
                    x-transition.opacity
                >
            @endforeach
        </div>
        @if($destination->images->count() > 1)
            <div class="mt-4 flex items-center justify-between gap-4">
                <button type="button" class="text-xs tracking-[0.15em] uppercase text-forest" @click="active = (active - 1 + {{ $destination->images->count() }}) % {{ $destination->images->count() }}">Prev</button>
                <div class="flex gap-2">
                    @foreach($destination->images as $index => $image)
                        <button type="button" class="h-1.5 w-6 transition-colors" :class="active === {{ $index }} ? 'bg-forest' : 'bg-sand-deep'" @click="active = {{ $index }}" aria-label="Image {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                <button type="button" class="text-xs tracking-[0.15em] uppercase text-forest" @click="active = (active + 1) % {{ $destination->images->count() }}">Next</button>
            </div>
        @endif
    </div>
</section>
@endif

<section class="bg-forest text-sand py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-4xl md:text-5xl">Enquire about this destination</h2>
        <p class="mt-3 text-sand/70">Tell us when you’d like to travel — we’ll craft a thoughtful itinerary.</p>
        <div class="mt-10">
            <livewire:enquiry-form :destination-id="$destination->id" />
        </div>
    </div>
</section>
@endsection
