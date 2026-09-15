@extends('layouts.public')

@section('title', config('app.name').' — Private journeys into Africa’s wild heart')
@section('meta_description', $heroSlides[0]['tagline'] ?? 'Private safari journeys across East Africa')

@section('content')
@php
    $slideCount = count($heroSlides);
    $heroDuration = 8000;
@endphp

<section
    class="relative min-h-[calc(100svh-5.5rem)] flex items-end overflow-hidden bg-forest"
    x-data="{
        index: 0,
        count: {{ $slideCount }},
        timer: null,
        duration: {{ $heroDuration }},
        next() { this.index = (this.index + 1) % this.count; this.restart() },
        prev() { this.index = (this.index - 1 + this.count) % this.count; this.restart() },
        go(i) { this.index = i; this.restart() },
        restart() {
            clearInterval(this.timer);
            if (this.count > 1) {
                this.timer = setInterval(() => { this.index = (this.index + 1) % this.count }, this.duration);
            }
        }
    }"
    x-init="restart()"
>
    @foreach($heroSlides as $i => $slide)
        <div
            class="hero-slide absolute inset-0"
            x-show="index === {{ $i }}"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-700"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @if($i !== 0) x-cloak @endif
        >
            @if(!empty($slide['video_url']))
                <video
                    class="absolute inset-0 h-full w-full object-cover scale-105"
                    muted
                    loop
                    playsinline
                    preload="{{ $i === 0 ? 'auto' : 'none' }}"
                    poster="{{ $slide['image_url'] }}"
                    data-parallax="0.08"
                    x-bind:autoplay="index === {{ $i }}"
                    @if($i === 0) autoplay @endif
                >
                    <source src="{{ $slide['video_url'] }}" type="video/mp4">
                </video>
            @elseif(!empty($slide['image_url']))
                <img
                    src="{{ $slide['image_url'] }}"
                    alt="{{ $slide['headline'] ?: 'Pearl Pulse Safaris' }}"
                    class="absolute inset-0 h-full w-full object-cover scale-105"
                    data-parallax="0.1"
                    @if($i > 0) loading="lazy" @endif
                >
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/75 via-charcoal/25 to-black/15"></div>
        </div>
    @endforeach

    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-20 lg:px-8 lg:pb-24">
        @foreach($heroSlides as $i => $slide)
            <div x-show="index === {{ $i }}" @if($i !== 0) x-cloak @endif>
                @if($slide['label'])
                    <p class="text-[11px] tracking-[0.22em] uppercase text-white/70 fade-up">{{ $slide['label'] }}</p>
                @endif
                <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl text-white leading-[0.95] max-w-4xl fade-up mt-4" style="animation-delay: 0.08s">
                    {{ $slide['headline'] }}
                </h1>
                @if($slide['tagline'])
                    <p class="mt-6 max-w-xl text-white/85 text-lg md:text-xl font-light leading-relaxed fade-up" style="animation-delay: 0.18s">
                        {{ $slide['tagline'] }}
                    </p>
                @endif
            </div>
        @endforeach

        <div class="mt-10 flex flex-wrap gap-4 fade-up" style="animation-delay: 0.28s">
            <a href="{{ route('plan') }}" class="btn-outline">Plan your journey</a>
            <button type="button" @click="$dispatch('open-journey-search')" class="text-sm tracking-[0.14em] uppercase text-white/80 hover:text-white self-center transition">
                Find your journey
            </button>
        </div>

        @if($slideCount > 1)
            @php
                $nextLabels = collect($heroSlides)->map(fn ($s) => $s['label'] ?: $s['headline'])->values()->all();
            @endphp
            <div class="mt-14 flex flex-wrap items-end justify-between gap-6 fade-up" style="animation-delay: 0.4s">
                <div class="min-w-[12rem] max-w-sm flex-1">
                    <p class="text-[10px] tracking-[0.2em] uppercase text-white/55 mb-2">Next up</p>
                    <p class="text-sm text-white/90 font-light truncate">
                        <span x-text="{{ Js::from($nextLabels) }}[(index + 1) % count]"></span>
                    </p>
                    <div class="hero-progress mt-3" style="--hero-duration: {{ $heroDuration }}ms">
                        <span :key="index"></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" @click="prev()" class="h-10 w-10 rounded-full border border-white/40 text-white hover:bg-white hover:text-forest transition" aria-label="Previous slide">‹</button>
                    <button type="button" @click="next()" class="h-10 w-10 rounded-full border border-white/40 text-white hover:bg-white hover:text-forest transition" aria-label="Next slide">›</button>
                </div>
            </div>
        @endif
    </div>
</section>

@if($homeIntroHeading || $homeIntroBody)
<section class="bg-white py-24 lg:py-32">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center reveal">
        @if($homeIntroEyebrow)
            <p class="section-eyebrow">{{ $homeIntroEyebrow }}</p>
        @endif
        @if($homeIntroHeading)
            <h2 class="font-display text-3xl md:text-4xl lg:text-5xl text-charcoal leading-snug">{{ $homeIntroHeading }}</h2>
        @endif
        @if($homeIntroBody)
            <p class="mt-6 text-muted text-lg leading-relaxed whitespace-pre-line">{{ $homeIntroBody }}</p>
        @endif
        <div class="mt-10">
            <a href="{{ route('about') }}" class="btn-outline-dark">Our story</a>
        </div>
    </div>
</section>
@endif

<section class="bg-cream py-24 lg:py-32 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl">
            <p class="section-eyebrow">{{ $destinationsEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $destinationsHeading }}</h2>
            @if($destinationsIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $destinationsIntro }}</p>
            @endif
        </div>

        <div class="mt-16 space-y-24 lg:space-y-32">
            @foreach($countries as $country)
                @php
                    $countryJourneys = $country->journeys->take(3);
                    $reverse = $loop->iteration % 2 === 0;
                @endphp
                <div class="grid gap-10 lg:grid-cols-12 lg:gap-14 items-center">
                    <a
                        href="{{ route('destinations.country', $country) }}"
                        class="group block media-pan reveal {{ $reverse ? 'lg:col-span-5 lg:col-start-8 reveal-right' : 'lg:col-span-5 reveal-left' }}"
                    >
                        <div class="overflow-hidden bg-forest/10 aspect-[4/5] sm:aspect-[5/4] lg:aspect-[4/5]">
                            @if($country->coverUrl())
                                <img
                                    src="{{ $country->coverUrl() }}"
                                    alt="{{ $country->name }}"
                                    class="h-full w-full object-cover scale-105"
                                    data-parallax="0.14"
                                    loading="lazy"
                                >
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-forest to-forest-light"></div>
                            @endif
                        </div>
                        <div class="mt-5">
                            <h3 class="font-display text-3xl md:text-4xl text-charcoal group-hover:text-forest transition">{{ $country->name }}</h3>
                            @if($country->teaser)
                                <p class="mt-2 text-sm text-muted leading-relaxed">{{ $country->teaser }}</p>
                            @endif
                        </div>
                    </a>

                    <div class="reveal {{ $reverse ? 'lg:col-span-6 lg:col-start-1 lg:row-start-1 reveal-left' : 'lg:col-span-6 lg:col-start-7 reveal-right' }}">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-5">Featured journeys</p>
                        @if($countryJourneys->isEmpty())
                            <p class="text-sm text-muted">Journeys for {{ $country->name }} are being prepared.</p>
                            <a href="{{ route('destinations.country', $country) }}" class="mt-4 inline-block text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70">Explore {{ $country->name }}</a>
                        @else
                            <div class="space-y-1 reveal-stagger">
                                @foreach($countryJourneys as $journey)
                                    <a href="{{ route('journeys.show', $journey) }}" class="journey-row group flex gap-5 items-start border-b border-charcoal/10 py-5 last:border-0">
                                        <div class="hidden sm:block w-28 h-20 shrink-0 overflow-hidden bg-forest/10">
                                            @if($journey->coverUrl())
                                                <img src="{{ $journey->coverUrl() }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-display text-2xl text-charcoal group-hover:text-forest transition">{{ $journey->name }}</h4>
                                            @if($journey->duration_label)
                                                <p class="mt-1 text-[11px] tracking-[0.16em] uppercase text-muted">{{ $journey->duration_label }}</p>
                                            @endif
                                            @if($journey->teaser)
                                                <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $journey->teaser }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('journeys.country', $country) }}" class="mt-6 inline-block text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">All {{ $country->name }} journeys →</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-white py-24 lg:py-32 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl">
            <p class="section-eyebrow">{{ $experiencesEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $experiencesHeading }}</h2>
            @if($experiencesIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $experiencesIntro }}</p>
            @endif
        </div>
    </div>

    @if($experiences->isNotEmpty())
        <div class="mt-12 marquee" data-marquee>
            <div class="flex gap-5 px-5 lg:px-8" data-marquee-inner>
                @foreach($experiences as $experience)
                    <a
                        href="{{ route('experiences.show', $experience) }}"
                        class="experience-tile group relative block overflow-hidden min-h-[20rem] w-[78vw] sm:w-[42vw] lg:w-[28vw] shrink-0 bg-forest"
                    >
                        @if($experience->coverUrl())
                            <img src="{{ $experience->coverUrl() }}" alt="{{ $experience->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/25 to-transparent"></div>
                        <div class="tile-copy relative z-10 flex h-full min-h-[20rem] flex-col justify-end p-6">
                            <h3 class="font-display text-3xl text-white">{{ $experience->name }}</h3>
                            @if($experience->teaser)
                                <p class="mt-2 text-sm text-white/75 line-clamp-2">{{ $experience->teaser }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mx-auto max-w-7xl px-5 lg:px-8 mt-12 reveal">
        <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
    </div>
</section>

<section class="bg-cream py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl">
            <p class="section-eyebrow">{{ $featuredEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $featuredHeading }}</h2>
            @if($featuredIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $featuredIntro }}</p>
            @endif
        </div>
        <div class="mt-12 grid gap-10 sm:grid-cols-3 reveal-stagger">
            @foreach($journeys as $journey)
                <div class="lift-card">
                    <x-journey-card :journey="$journey" />
                </div>
            @endforeach
        </div>
        <div class="mt-14 reveal">
            <a href="{{ route('journeys.index') }}" class="btn-primary">All journeys</a>
        </div>
    </div>
</section>

<section class="bg-forest text-sand py-24 lg:py-28 overflow-hidden relative">
    <div class="absolute inset-0 opacity-30 pointer-events-none" aria-hidden="true">
        <div class="absolute -right-20 top-10 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -left-16 bottom-0 h-56 w-56 rounded-full bg-sand/10 blur-3xl"></div>
    </div>
    <div class="relative mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <p class="text-[11px] tracking-[0.22em] uppercase text-sand/50 mb-3">Journey Finder</p>
        <h2 class="font-display text-4xl md:text-5xl text-white">Not sure where to begin?</h2>
        <p class="mt-4 text-sand/75 leading-relaxed">Refine by country, duration, experience, and stay style — then we tailor from there.</p>
        <div class="mt-10 flex flex-wrap gap-4">
            <a href="{{ route('journeys.finder') }}" class="btn-outline">Open the finder</a>
            <button type="button" @click="$dispatch('open-journey-search')" class="text-sm tracking-[0.14em] uppercase text-sand/80 hover:text-white self-center transition">Quick search</button>
        </div>
    </div>
</section>

@if(count($homePillars))
<section class="bg-white py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl">
            <p class="section-eyebrow">Why travel with Pearl Pulse</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">What sets the journey apart</h2>
        </div>
        <div class="mt-14 grid gap-10 md:grid-cols-2 lg:grid-cols-3 reveal-stagger">
            @foreach($homePillars as $pillar)
                <div class="border-t border-charcoal/10 pt-6">
                    <h3 class="font-display text-2xl text-charcoal">{{ $pillar['title'] }}</h3>
                    <p class="mt-3 text-sm text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($reviews->isNotEmpty())
<section class="bg-cream py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-12 reveal">
            <div>
                <p class="section-eyebrow">Guest voices</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">Stories from the field</h2>
            </div>
            <div class="flex gap-4 text-sm">
                @if(!empty($reviewLinks['google']))
                    <a href="{{ $reviewLinks['google'] }}" class="tracking-[0.12em] uppercase text-forest hover:opacity-70 transition" target="_blank" rel="noopener">Google reviews</a>
                @endif
                @if(!empty($reviewLinks['tripadvisor']))
                    <a href="{{ $reviewLinks['tripadvisor'] }}" class="tracking-[0.12em] uppercase text-forest hover:opacity-70 transition" target="_blank" rel="noopener">Tripadvisor</a>
                @endif
                <a href="{{ route('true-pulse') }}" class="tracking-[0.12em] uppercase text-forest hover:opacity-70 transition">True Pulse</a>
            </div>
        </div>
        <div class="grid gap-10 md:grid-cols-2 reveal-stagger">
            @foreach($reviews as $review)
                <blockquote class="border-t border-charcoal/10 pt-6">
                    <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                    <footer class="mt-5 text-sm text-muted">
                        <span class="text-charcoal">{{ $review->guest_name }}</span>
                        @if($review->guest_country)
                            · {{ $review->guest_country }}
                        @endif
                        @if($review->journey)
                            · <a href="{{ route('journeys.show', $review->journey) }}" class="hover:text-forest transition">{{ $review->journey->name }}</a>
                        @endif
                    </footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="border-t border-charcoal/8 bg-white py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow reveal">Continue</p>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 reveal-stagger">
            <a href="{{ route('our-people') }}" class="group block lift-card">
                <h3 class="font-display text-2xl text-charcoal group-hover:text-forest transition">Our people</h3>
                <p class="mt-2 text-sm text-muted">Guides and planners who live this work.</p>
            </a>
            <a href="{{ route('true-pulse') }}" class="group block lift-card">
                <h3 class="font-display text-2xl text-charcoal group-hover:text-forest transition">True Pulse</h3>
                <p class="mt-2 text-sm text-muted">Guest photographs and stories we have approved.</p>
            </a>
            <a href="{{ route('stays.index') }}" class="group block lift-card">
                <h3 class="font-display text-2xl text-charcoal group-hover:text-forest transition">Selected stays</h3>
                <p class="mt-2 text-sm text-muted">Lodges we choose. We do not own them.</p>
            </a>
            <a href="{{ route('insiders.index') }}" class="group block lift-card">
                <h3 class="font-display text-2xl text-charcoal group-hover:text-forest transition">Insiders</h3>
                <p class="mt-2 text-sm text-muted">Guides from the ground — permits, seasons, packing.</p>
            </a>
        </div>
    </div>
</section>

<div class="reveal">
    <x-page-cta :heading="$homeCtaHeading" :text="$homeCtaText" :button="$homeCtaButton">
        @if($whatsappUrl)
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-sm tracking-[0.14em] uppercase text-sand/70 hover:text-sand">WhatsApp</a>
        @endif
    </x-page-cta>
</div>
@endsection
