@extends('layouts.public')

@section('title', $journey->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($journey->seoDescription(), 160))

@section('content')
@php
    $cover = $journey->coverUrl();
    $video = $journey->videoPlaybackUrl();
@endphp
<section class="relative min-h-[70svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$journey->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Journeys', 'href' => route('journeys.index')],
            ['label' => $journey->name],
        ]" />
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-4">{{ $journey->name }}</h1>
        @if($journey->subtitle)
            <p class="mt-3 text-lg text-sand/80 font-light">{{ $journey->subtitle }}</p>
        @endif
    </div>
</section>

<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-7">
            @if($journey->teaser)
                <p class="font-display text-2xl md:text-3xl text-forest leading-snug">{{ $journey->teaser }}</p>
            @endif
            <div class="prose-safari mt-8">{!! $journey->overview !!}</div>
        </div>
        <aside class="lg:col-span-5 lg:pl-8">
            <div class="border-t border-sand-deep/40 pt-8 space-y-5">
                <h2 class="text-xs tracking-[0.2em] uppercase text-gold mb-6">At a glance</h2>
                @if($journey->duration_label)
                    <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Days</dt><dd class="mt-1 text-forest">{{ $journey->duration_label }}</dd></div>
                @endif
                <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Investment</dt><dd class="mt-1 text-forest">{{ $journey->priceLabel() }}</dd></div>
                @if($journey->best_time)
                    <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Best time</dt><dd class="mt-1 text-forest">{{ $journey->best_time }}</dd></div>
                @endif
                @if($journey->countries->isNotEmpty())
                    <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">Countries</dt><dd class="mt-1 text-forest">{{ $journey->countries->pluck('name')->join(', ') }}</dd></div>
                @endif
                @foreach($journey->highlights ?? [] as $item)
                    <div><dt class="text-xs tracking-[0.15em] uppercase text-muted">{{ $item['label'] ?? '' }}</dt><dd class="mt-1 text-forest">{{ $item['value'] ?? '' }}</dd></div>
                @endforeach
            </div>
        </aside>
    </div>
</section>

@if($journey->destinations->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Destinations</h2>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($journey->destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($journey->itinerary))
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Day by day</h2>
        <ol class="space-y-8">
            @foreach($journey->itinerary as $day)
                <li class="border-t border-sand-deep/30 pt-6">
                    <p class="text-xs tracking-[0.18em] uppercase text-gold">Day {{ $day['day'] ?? $loop->iteration }}</p>
                    <h3 class="font-display text-2xl text-forest mt-1">{{ $day['title'] ?? '' }}</h3>
                    <p class="mt-2 text-muted">{{ $day['description'] ?? '' }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

@if($journey->map_embed_url)
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Map</h2>
        <div class="aspect-[16/9] bg-forest/5">
            <iframe src="{{ $journey->map_embed_url }}" class="h-full w-full border-0" loading="lazy" title="Journey map"></iframe>
        </div>
    </div>
</section>
@endif

@if($journey->stays->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-3">Selected stays</h2>
        <p class="text-muted mb-8">Places we have selected — we do not own these lodges.</p>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($journey->stays as $stay)
                <x-stay-card :stay="$stay" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($journey->experiences->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Experiences</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($journey->experiences as $experience)
                <a href="{{ route('experiences.show', $experience) }}" class="border border-sand-deep px-4 py-2 text-sm text-forest hover:border-forest">{{ $experience->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 md:grid-cols-2">
        <div>
            <h2 class="font-display text-3xl text-forest mb-4">What’s included</h2>
            <ul class="space-y-2 text-muted">
                @foreach($journey->included ?? [] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <h2 class="font-display text-3xl text-forest mb-4">What’s not included</h2>
            <ul class="space-y-2 text-muted">
                @foreach($journey->not_included ?? [] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@if($journey->practical)
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-3xl text-forest mb-4">Practical information</h2>
        <div class="prose-safari">{!! $journey->practical !!}</div>
    </div>
</section>
@endif

@if($journey->images->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-4 sm:grid-cols-2">
        @foreach($journey->images as $image)
            <img src="{{ $image->url() }}" alt="{{ $image->alt ?: $journey->name }}" class="w-full aspect-[16/10] object-cover" loading="lazy">
        @endforeach
    </div>
</section>
@endif

<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Guest reviews</h2>

        @if($journey->reviews->isNotEmpty())
            <div class="grid gap-8 md:grid-cols-2 mb-12">
                @foreach($journey->reviews as $review)
                    <blockquote>
                        <p class="font-display text-2xl text-forest">“{{ $review->quote }}”</p>
                        <footer class="mt-3 text-sm text-muted">
                            {{ $review->guest_name }}@if($review->guest_country)<span> · {{ $review->guest_country }}</span>@endif
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-10 max-w-xl">Travellers who have taken this journey will share their experiences here once reviews are approved.</p>
        @endif

        <div class="max-w-2xl">
            <livewire:review-form :journey="$journey" :key="'review-form-'.$journey->id" />
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Related journeys</h2>
        <div class="grid gap-8 sm:grid-cols-3">
            @foreach($related as $item)
                <x-journey-card :journey="$item" />
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta
    heading="Make this journey yours"
    text="Customize this journey — we will reshape days, stays, and pace around you."
    button="Customize this journey"
    :href="route('plan', ['journey' => $journey->slug])"
/>

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'TouristTrip',
    'name' => $journey->name,
    'description' => $journey->seoDescription(),
    'touristType' => 'Safari',
    'itinerary' => array_map(fn ($d) => ['@type' => 'TouristDestination', 'name' => $d['title'] ?? ''], $journey->itinerary ?? []),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endsection
