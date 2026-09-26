@extends('layouts.public')

@section('title', $journey->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($journey->seoDescription(), 160))

@section('content')
@php
    $cover = $journey->coverUrl();
    $video = $journey->videoPlaybackUrl();
    $dayCount = is_array($journey->itinerary) ? count($journey->itinerary) : 0;
@endphp
<section class="relative min-h-[72svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$journey->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/35 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Journeys', 'href' => route('journeys.index')],
            ['label' => $journey->name],
        ]" />
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-sand mt-4 fade-up leading-[0.95]">{{ $journey->name }}</h1>
        @if($journey->subtitle)
            <p class="mt-4 text-lg md:text-xl text-sand/80 font-light max-w-2xl fade-up" style="animation-delay: 0.1s">{{ $journey->subtitle }}</p>
        @endif
    </div>
</section>

<section class="surface surface--white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-7 reveal">
            @if($journey->teaser)
                <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">{{ $journey->teaser }}</p>
            @endif

            @if(!empty($journey->highlights))
                <ul class="mt-10 space-y-4">
                    @foreach($journey->highlights as $item)
                        <li class="flex gap-4 border-t border-charcoal/10 pt-4">
                            <span class="text-[11px] tracking-[0.16em] uppercase text-muted shrink-0 w-28">{{ $item['label'] ?? 'Highlight' }}</span>
                            <span class="text-charcoal">{{ $item['value'] ?? '' }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="prose-safari mt-10">{!! $journey->overview !!}</div>
        </div>
        <aside class="lg:col-span-5 lg:pl-4">
            <div class="journey-glance surface surface--beige p-7 lg:p-8">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <h2 class="text-[11px] tracking-[0.2em] uppercase text-muted">At a glance</h2>
                    <livewire:favorite-button :journey="$journey" />
                </div>
                <dl class="space-y-5">
                    @if($journey->duration_label)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Days</dt>
                            <dd class="mt-1 text-charcoal font-display text-2xl">{{ $journey->duration_label }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs tracking-[0.15em] uppercase text-muted">Investment</dt>
                        <dd class="mt-1 text-charcoal">{{ $journey->priceLabel() }}</dd>
                    </div>
                    @if($journey->best_time)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Best time</dt>
                            <dd class="mt-1 text-charcoal">{{ $journey->best_time }}</dd>
                        </div>
                    @endif
                    @if($journey->countries->isNotEmpty())
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Countries</dt>
                            <dd class="mt-1 text-charcoal">{{ $journey->countries->pluck('name')->join(', ') }}</dd>
                        </div>
                    @endif
                </dl>
                <a href="{{ route('plan', ['journey' => $journey->slug]) }}" class="btn-primary mt-8 w-full text-center">Customize this journey</a>
            </div>
        </aside>
    </div>
</section>

@if($journey->destinations->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Destinations</h2>
        <div class="editorial-rail reveal-stagger">
            @foreach($journey->destinations as $destination)
                <x-destination-card :destination="$destination" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if(!empty($journey->itinerary))
<section
    class="surface surface--white py-16 lg:py-24"
    x-data="{ active: 0 }"
>
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-10 reveal">Day by day</h2>
        <ol class="day-track reveal">
            @foreach($journey->itinerary as $i => $day)
                <li
                    class="day-track__item"
                    :class="{ 'is-active': active === {{ $i }} }"
                    @mouseenter="active = {{ $i }}"
                    @focusin="active = {{ $i }}"
                >
                    <span class="day-track__mark">{{ $day['day'] ?? $loop->iteration }}</span>
                    <p class="text-xs tracking-[0.18em] uppercase text-muted">Day {{ $day['day'] ?? $loop->iteration }}</p>
                    <h3 class="font-display text-2xl md:text-3xl text-charcoal mt-1">{{ $day['title'] ?? '' }}</h3>
                    @if(!empty($day['description']))
                        <p class="mt-2 text-muted leading-relaxed">{{ $day['description'] }}</p>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

@if($journey->map_embed_url)
<section class="surface surface--white pb-4">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 mb-6">
        <h2 class="font-display text-4xl text-charcoal reveal">Map</h2>
    </div>
    <div class="aspect-[16/9] lg:aspect-[21/9] bg-forest/5">
        <iframe src="{{ $journey->map_embed_url }}" class="h-full w-full border-0" loading="lazy" title="Journey map"></iframe>
    </div>
</section>
@endif

@if($journey->stays->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mb-8 max-w-2xl">
            <h2 class="font-display text-4xl text-charcoal">Selected stays</h2>
            <p class="mt-3 text-muted">Places we have selected for their location, character and ability to complement your journey. We do not own these lodges.</p>
        </div>
        <div class="editorial-rail reveal-stagger">
            @foreach($journey->stays as $stay)
                <x-stay-card :stay="$stay" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($journey->experiences->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Experiences</h2>
        <div class="filmstrip reveal-stagger">
            @foreach($journey->experiences as $experience)
                <x-experience-card :experience="$experience" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-12 md:grid-cols-2 reveal">
        <div>
            <h2 class="font-display text-3xl text-charcoal mb-5">What’s included</h2>
            <ul class="space-y-3 text-muted">
                @foreach($journey->included ?? [] as $item)
                    <li class="flex gap-3"><span class="text-forest mt-1" aria-hidden="true">—</span><span>{{ $item }}</span></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h2 class="font-display text-3xl text-charcoal mb-5">What’s not included</h2>
            <ul class="space-y-3 text-muted">
                @foreach($journey->not_included ?? [] as $item)
                    <li class="flex gap-3"><span class="text-forest/40 mt-1" aria-hidden="true">—</span><span>{{ $item }}</span></li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@if($journey->practical)
<section class="surface surface--white py-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <h2 class="font-display text-3xl text-charcoal mb-4">Practical information</h2>
        <div class="prose-safari">{!! $journey->practical !!}</div>
    </div>
</section>
@endif

@if($journey->images->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Gallery</h2>
        <div class="gallery-mosaic reveal-stagger">
            @foreach($journey->images as $image)
                <img src="{{ $image->url() }}" alt="{{ $image->alt ?: $journey->name }}" loading="lazy" decoding="async">
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="surface surface--brown py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Guest reviews</h2>

        @if($journey->reviews->isNotEmpty())
            <div class="grid gap-10 md:grid-cols-2 mb-12 reveal-stagger">
                @foreach($journey->reviews as $review)
                    <blockquote>
                        <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                        <footer class="mt-4 text-sm text-muted">
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
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Related journeys</h2>
        <div class="filmstrip reveal-stagger">
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
