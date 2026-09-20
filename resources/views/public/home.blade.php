@extends('layouts.public')

@section('title', config('app.name').' — Private journeys into Africa’s wild heart')
@section('meta_description', $heroSlides[0]['tagline'] ?? 'Private safari journeys across East Africa')

@section('content')
@php
    $slideCount = count($heroSlides);
    $heroDuration = 8000;
@endphp

<section
    class="relative min-h-[calc(76svh-5.5rem)] flex items-end overflow-hidden bg-forest"
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
            @php
                $youtubeEmbed = \App\Support\VideoUrl::youtubeEmbedUrl($slide['video_url'] ?? null);
                $youtubePoster = \App\Support\VideoUrl::youtubePosterUrl($slide['video_url'] ?? null);
            @endphp
            @if($youtubeEmbed)
                <div class="hero-youtube absolute inset-0 overflow-hidden" aria-hidden="true">
                    @if($youtubePoster)
                        <img
                            src="{{ $youtubePoster }}"
                            alt=""
                            class="absolute inset-0 h-full w-full object-cover scale-105"
                            @if($i > 0) loading="lazy" @endif
                        >
                    @endif
                    <iframe
                        class="hero-youtube__frame"
                        x-bind:src="index === {{ $i }} ? @js($youtubeEmbed) : ''"
                        title="{{ $slide['headline'] ?: ($slide['label'] ?: 'Pearl Pulse Safaris') }}"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin"
                        allowfullscreen
                    ></iframe>
                </div>
            @elseif(!empty($slide['video_url']))
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
        </div>    @endforeach

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

<section class="trust-strip">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-8 gap-y-3 px-5 py-4 text-center lg:px-8">
        <div class="trust-strip__item">
            <span class="trust-strip__icon" aria-hidden="true">✓</span>
            <span>Gorilla tracking</span>
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon" aria-hidden="true">✓</span>
            <span>Great migration</span>
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon" aria-hidden="true">✓</span>
            <span>Kenya, Tanzania & Uganda</span>
        </div>
        <div class="trust-strip__item">
            <span class="trust-strip__icon" aria-hidden="true">✓</span>
            <span>Iconic private journeys</span>
        </div>
    </div>
</section>

@if($homeIntroHeading || $homeIntroBody)
<section class="bg-white py-10 lg:py-12">
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
        <div class="mt-8">
            <a href="{{ route('about') }}" class="btn-outline-dark">Our story</a>
        </div>
    </div>
</section>
@endif

<section class="relative bg-cream py-16 lg:py-20 overflow-hidden">
    <x-section-edge placement="top" variant="wave" class="text-cream" />
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center">
            <div class="mb-4 flex justify-center">
                <x-path-accent />
            </div>
            <p class="section-eyebrow">{{ $destinationsEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $destinationsHeading }}</h2>
            @if($destinationsIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $destinationsIntro }}</p>
            @endif
        </div>

        @if($countries->isNotEmpty())
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5 reveal-stagger">
                @foreach($countries as $country)
                    <x-country-card :country="$country" overlay />
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex justify-center reveal">
            <a href="{{ route('destinations.index') }}" class="btn-outline-dark">All destinations</a>
        </div>
    </div>
    <x-section-edge variant="ridge" class="text-white" />
</section>

<section class="relative bg-white py-24 lg:py-32 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center">
            <div class="mb-4 flex justify-center">
                <x-path-accent class="opacity-70" />
            </div>
            <p class="section-eyebrow">{{ $experiencesEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $experiencesHeading }}</h2>
            @if($experiencesIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $experiencesIntro }}</p>
            @endif
        </div>
    </div>

    @if($experiences->isNotEmpty())
        <div class="mt-12 marquee" data-marquee>
            <div class="marquee__track" data-marquee-inner>
                @foreach([false, true] as $isClone)
                    <div class="marquee__group" @if($isClone) aria-hidden="true" @endif>
                        @foreach($experiences as $experience)
                            <a
                                href="{{ route('experiences.show', $experience) }}"
                                class="experience-tile group relative block overflow-hidden min-h-[20rem] w-[78vw] sm:w-[42vw] lg:w-[22rem] xl:w-[26rem] shrink-0 bg-forest"
                                @if($isClone) tabindex="-1" @endif
                            >
                                @if($experience->coverThumbUrl() || $experience->coverUrl())
                                    <img src="{{ $experience->coverThumbUrl() ?: $experience->coverUrl() }}" alt="{{ $isClone ? '' : $experience->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy" decoding="async">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/25 to-transparent"></div>
                                <div class="tile-copy relative z-10 flex h-full min-h-[20rem] flex-col justify-end p-6">
                                    <span class="mb-4 inline-flex h-9 w-9 items-center justify-center rounded-xl border border-white/55 text-white transition group-hover:bg-white group-hover:text-charcoal" aria-hidden="true">→</span>
                                    <h3 class="font-display text-3xl text-white">{{ $experience->name }}</h3>
                                    @if($experience->teaser)
                                        <p class="mt-2 text-sm text-white/75 line-clamp-2">{{ $experience->teaser }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mx-auto max-w-7xl px-5 lg:px-8 mt-12 flex justify-center reveal">
        <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
    </div>
    <x-section-edge variant="wave" class="text-cream" />
</section>

<section class="relative bg-cream py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl">
            <p class="section-eyebrow">{{ $featuredEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $featuredHeading }}</h2>
            @if($featuredIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $featuredIntro }}</p>
            @endif
        </div>
        <div class="mt-12 grid gap-8 sm:grid-cols-3 reveal-stagger">
            @foreach($journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
        <div class="mt-14 reveal">
            <a href="{{ route('journeys.index') }}" class="btn-primary">All journeys</a>
        </div>
    </div>
    <x-section-edge variant="ridge" class="text-forest" />
</section>

<section class="bg-forest text-sand py-20 lg:py-24 overflow-hidden relative surface-nature">
    <div class="relative mx-auto max-w-3xl px-5 lg:px-8 reveal text-center">
        <p class="text-[11px] tracking-[0.22em] uppercase text-sand/50 mb-3">Journey Finder</p>
        <h2 class="font-display text-4xl md:text-5xl text-white">Not sure where to begin?</h2>
        <p class="mt-4 text-sand/75 leading-relaxed">Refine by country, duration, experience, and stay style — then we tailor from there.</p>
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('journeys.finder') }}" class="btn-outline">Open the finder</a>
            <button type="button" @click="$dispatch('open-journey-search')" class="text-sm tracking-[0.14em] uppercase text-sand/80 hover:text-white self-center transition">Quick search</button>
        </div>
    </div>

    @if(count($homePillars))
        <div class="relative mx-auto mt-14 max-w-7xl px-5 lg:px-8">
            <div class="feature-strip reveal-stagger rounded-2xl border border-white/15 bg-white/5 backdrop-blur-[2px]">
                @foreach($homePillars as $index => $pillar)
                    <div class="feature-strip__item">
                        <span class="feature-strip__mark">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <h3 class="font-display text-2xl text-white">{{ $pillar['title'] }}</h3>
                        <p class="mt-3 text-sm text-sand/75 leading-relaxed">{{ $pillar['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <x-section-edge variant="torn" class="{{ $reviews->isNotEmpty() ? 'text-cream' : 'text-white' }}" />
</section>

@if($reviews->isNotEmpty())
<section class="relative bg-cream py-24 lg:py-32">
    <x-section-edge placement="top" variant="wave" class="text-cream" />
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
        <div class="marquee reveal" data-marquee>
            <div class="marquee__track" data-marquee-inner>
                @foreach([false, true] as $isClone)
                    <div class="marquee__group" @if($isClone) aria-hidden="true" @endif>
                        @foreach($reviews as $review)
                            <blockquote class="w-[82vw] shrink-0 rounded-2xl border border-charcoal/10 bg-white p-6 sm:w-[28rem] lg:w-[32rem]">
                                <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                                <footer class="mt-6 border-t border-charcoal/10 pt-4 text-sm text-muted">
                                    <span class="text-charcoal">{{ $review->guest_name }}</span>
                                    @if($review->guest_country)
                                        · {{ $review->guest_country }}
                                    @endif
                                    @if($review->journey)
                                        · <a href="{{ route('journeys.show', $review->journey) }}" class="hover:text-forest transition" @if($isClone) tabindex="-1" @endif>{{ $review->journey->name }}</a>
                                    @endif
                                    <span class="mt-2 block text-[10px] uppercase tracking-[0.18em] text-muted">Guest review</span>
                                </footer>
                            </blockquote>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <x-section-edge variant="ridge" class="text-white" />
</section>
@endif

<section class="bg-white py-20">
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

<x-page-cta :heading="$homeCtaHeading" :text="$homeCtaText" :button="$homeCtaButton">
    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-sm tracking-[0.14em] uppercase text-white/75 hover:text-white transition [text-shadow:0_1px_12px_rgb(0_0_0_/_0.4)]">WhatsApp</a>
    @endif
</x-page-cta>
@endsection
