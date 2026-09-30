@extends('layouts.public')

@section('title', config('app.name').' — Private journeys into Africa’s wild heart')
@section('meta_description', $heroSlides[0]['tagline'] ?? 'Private safari journeys across East Africa')

@section('content')
@php
    $slideCount = count($heroSlides);
    $heroDuration = 8000;
    $trustItems = $trustItems ?? [];
    if ($trustItems === []) {
        $trustItems = [
            ['title' => 'Gorilla permits secured'],
            ['title' => 'Private vehicles only'],
            ['title' => 'Local expert guides'],
            ['title' => 'Uganda-based'],
        ];
    }
    $defaultPillars = [
        ['title' => 'Private by design', 'text' => 'Your vehicle, your pace, your group — never a shared seat sale. Days unfold around how you want to travel, not a fixed departure list.'],
        ['title' => 'Local knowledge', 'text' => 'Uganda-based specialists with deep East Africa relationships — parks, camps, and guides we trust to read the land and look after you.'],
        ['title' => 'Seamless logistics', 'text' => 'Gorilla permits, lodge holds, transfers, and timing handled end to end, so you arrive ready to look up — not down at a checklist.'],
        ['title' => 'Conservation-minded', 'text' => 'Travel that supports communities and wild places: permits that fund protection, local employment, and routes that keep impact light.'],
        ['title' => 'Honest counsel', 'text' => 'We say no when a trip does not fit, and reshape when it can — clear advice on seasons, pace, and what is worth the journey.'],
    ];
    $pillars = ($homePillars ?? []) !== [] ? $homePillars : $defaultPillars;
@endphp

{{-- 1. Hero --}}
<section
    class="home-hero relative flex flex-col overflow-hidden bg-forest"
    x-data="{
        index: 0,
        count: {{ $slideCount }},
        timer: null,
        duration: {{ $heroDuration }},
        labels: {{ Js::from(collect($heroSlides)->map(fn ($s) => $s['label'] ?: ($s['headline'] ?: 'Pearl Pulse'))->values()->all()) }},
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
                            decoding="async"
                            @if($i === 0) fetchpriority="high" @else loading="lazy" @endif
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
                    autoplay
                    preload="{{ $i === 0 ? 'metadata' : 'none' }}"
                    poster="{{ $slide['image_url'] }}"
                    data-parallax="0.08"
                    x-bind:autoplay="index === {{ $i }}"
                    x-init="$watch('index', (value) => {
                        if (value === {{ $i }}) { $el.play?.().catch(() => {}); }
                        else { $el.pause?.(); }
                    })"
                >
                    <source
                        x-bind:src="index === {{ $i }} ? @js($slide['video_url']) : ''"
                        type="video/mp4"
                    >
                </video>
            @elseif(!empty($slide['image_url']))
                <img
                    src="{{ $slide['image_url'] }}"
                    alt="{{ $slide['headline'] ?: 'Pearl Pulse Safaris' }}"
                    class="absolute inset-0 h-full w-full object-cover scale-105"
                    data-parallax="0.1"
                    decoding="async"
                    @if($i === 0) fetchpriority="high" @else loading="lazy" @endif
                >
            @else
                <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
            @endif
            <div class="hero-slide__shade absolute inset-0 bg-gradient-to-t from-charcoal/55 via-charcoal/20 to-transparent"></div>
        </div>
    @endforeach

    <div class="relative z-10 flex min-h-full flex-1 flex-col justify-end">
        <div class="home-hero__content relative mt-auto w-full">
            @foreach($heroSlides as $i => $slide)
                <div class="home-hero__copy" x-show="index === {{ $i }}" @if($i !== 0) x-cloak @endif>
                    <h1 class="home-hero__title font-display text-white leading-[1.05] max-w-3xl fade-up">
                        {{ $slide['headline'] ?: "Private journeys into Africa's wild heart" }}
                    </h1>
                    @if($slide['tagline'] || $i === 0)
                        <p class="home-hero__tagline max-w-lg text-white/85 font-light leading-relaxed fade-up" style="animation-delay: 0.1s">
                            {{ $slide['tagline'] ?: 'Uganda-based specialists. East Africa, designed around you.' }}
                        </p>
                    @endif
                </div>
            @endforeach

            <div class="home-hero__actions fade-up" style="animation-delay: 0.18s">
                <a href="{{ route('plan') }}" class="btn-outline home-hero__btn">Plan your journey</a>
                <a href="{{ route('journeys.finder') }}" class="btn-outline home-hero__btn home-hero__btn--ghost">Find your journey</a>
            </div>

            @if($slideCount > 1)
                <div class="home-hero__meta fade-up" style="animation-delay: 0.28s">
                    <div class="home-hero__now">
                        <p class="home-hero__now-label" x-text="labels[index]"></p>
                        <div class="hero-progress mt-2.5" style="--hero-duration: {{ $heroDuration }}ms">
                            <span :key="index"></span>
                        </div>
                    </div>
                    <div class="home-hero__nav">
                        <button type="button" @click="prev()" class="home-hero__nav-btn" aria-label="Previous slide">‹</button>
                        <button type="button" @click="next()" class="home-hero__nav-btn" aria-label="Next slide">›</button>
                    </div>
                </div>
            @elseif($slideCount === 1)
                <div class="home-hero__meta fade-up" style="animation-delay: 0.28s">
                    <div class="home-hero__now">
                        <p class="home-hero__now-label">{{ $heroSlides[0]['label'] ?: 'Pearl Pulse Safaris' }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. Trust bar — three keywords in one row --}}
        <div class="home-hero__trust relative z-10">
            <div class="home-hero__trust-row">
                @foreach(array_slice($trustItems, 0, 3) as $item)
                    <span>{{ $item['title'] }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 3. Short intro --}}
@if($homeIntroHeading || $homeIntroBody)
<section class="surface surface--white py-12 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center reveal">
        @if($homeIntroEyebrow)
            <p class="section-eyebrow">{{ $homeIntroEyebrow }}</p>
        @endif
        @if($homeIntroHeading)
            <h2 class="home-section-title home-intro__title">{{ $homeIntroHeading }}</h2>
        @endif
        @if($homeIntroBody)
            <p class="home-section-lead whitespace-pre-line">{{ $homeIntroBody }}</p>
        @endif
        <div class="mt-6 sm:mt-8">
            <a href="{{ route('about') }}" class="btn-outline-dark home-intro__cta">Discover Pearl Pulse</a>
        </div>
    </div>
</section>
@endif

{{-- 4. Destinations — country tabs + feature panel (client original) --}}
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-5xl text-center max-lg:mx-0 max-lg:text-left">
            <p class="section-eyebrow">{{ $destinationsEyebrow }}</p>
            <h2 class="home-section-title">{{ $destinationsHeading }}</h2>
            @if($destinationsIntro)
                <p class="home-section-lead max-lg:max-w-xl mx-auto max-lg:mx-0">{{ $destinationsIntro }}</p>
            @endif
        </div>

        @if($destinationPanels->isNotEmpty())
            <div
                class="mt-10"
                x-data="{
                    active: '{{ $destinationPanels->first()['key'] }}',
                    seen: { '{{ $destinationPanels->first()['key'] }}': true }
                }"
            >
                <nav class="country-navbar reveal" aria-label="Countries">
                    <div class="country-navbar__track" role="tablist">
                        @foreach($destinationPanels as $panel)
                            <button
                                type="button"
                                id="country-tab-{{ $panel['key'] }}"
                                class="country-navbar__tab{{ $loop->first ? ' is-active' : '' }}"
                                role="tab"
                                :class="{ 'is-active': active === '{{ $panel['key'] }}' }"
                                :aria-selected="(active === '{{ $panel['key'] }}').toString()"
                                aria-controls="country-panel-{{ $panel['key'] }}"
                                @click="
                                    active = '{{ $panel['key'] }}';
                                    seen['{{ $panel['key'] }}'] = true;
                                    $el.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
                                    $nextTick(() => document.getElementById('country-carousel-{{ $panel['key'] }}')?.scrollTo({ left: 0 }));
                                "
                            >{{ $panel['label'] }}</button>
                        @endforeach
                    </div>
                </nav>

                @foreach($destinationPanels as $panel)
                    @php
                        $items = $panel['items'];
                        $slideCount = $items->count();
                    @endphp
                    <div
                        id="country-panel-{{ $panel['key'] }}"
                        role="tabpanel"
                        aria-labelledby="country-tab-{{ $panel['key'] }}"
                        x-show="active === '{{ $panel['key'] }}'"
                        @if(! $loop->first) x-cloak @endif
                    >
                        <div
                            id="country-carousel-{{ $panel['key'] }}"
                            class="destination-carousel lg:hidden"
                        >
                            @foreach($items as $item)
                                <a href="{{ $item['href'] }}" class="destination-slide">
                                    @if($item['thumb'] ?? $item['image'])
                                        <img
                                            @if($loop->parent->first)
                                                src="{{ $item['thumb'] ?? $item['image'] }}"
                                            @else
                                                x-bind:src="seen['{{ $panel['key'] }}'] ? @js($item['thumb'] ?? $item['image']) : null"
                                            @endif
                                            alt="{{ $item['title'] }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-forest to-forest-light"></div>
                                    @endif
                                    <div class="destination-slide__shade"></div>
                                    <div class="destination-slide__copy">
                                        <p class="text-[0.75rem] tracking-[0.18em] uppercase text-white/80">{{ $item['kicker'] }}</p>
                                        <h3 class="font-display text-3xl text-white leading-tight mt-1">{{ $item['title'] }}</h3>
                                        @if($item['teaser'])
                                            <p class="mt-2 text-base text-white/90 leading-relaxed line-clamp-3">{{ $item['teaser'] }}</p>
                                        @endif
                                        <span class="destination-slide__cta" aria-hidden="true">Explore</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-5 lg:hidden">
                            <a href="{{ $panel['href'] }}" class="home-text-link">{{ $panel['explore'] }} →</a>
                        </div>

                        <div
                            class="destination-block hidden lg:grid grid-cols-2 gap-10 xl:gap-14 items-start"
                            x-data="{
                                index: 0,
                                count: {{ $slideCount }},
                                timer: null,
                                duration: 4000,
                                paused: false,
                                next() { this.index = (this.index + 1) % this.count; this.restart() },
                                prev() { this.index = (this.index - 1 + this.count) % this.count; this.restart() },
                                go(i) { this.index = i; this.restart() },
                                restart() {
                                    clearInterval(this.timer);
                                    if (this.count > 1 && !this.paused) {
                                        this.timer = setInterval(() => { this.index = (this.index + 1) % this.count }, this.duration);
                                    }
                                },
                                pause() { this.paused = true; clearInterval(this.timer) },
                                resume() { this.paused = false; this.restart() }
                            }"
                            x-init="restart()"
                            @mouseenter="pause()"
                            @mouseleave="resume()"
                            @focusin="pause()"
                            @focusout="resume()"
                        >
                            <div class="country-panel__media relative overflow-hidden bg-forest aspect-[4/5] min-h-[36rem]">
                                @foreach($items as $i => $item)
                                    <a
                                        href="{{ $item['href'] }}"
                                        class="absolute inset-0 block"
                                        x-show="index === {{ $i }}"
                                        x-transition:enter="transition ease-out duration-700"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        @if($i !== 0) x-cloak @endif
                                    >
                                        @if($item['image'] || $item['thumb'])
                                            <img
                                                @if($loop->parent->first && $i === 0)
                                                    src="{{ $item['thumb'] ?? $item['image'] }}"
                                                    fetchpriority="low"
                                                @else
                                                    x-bind:src="(active === '{{ $panel['key'] }}' && (index === {{ $i }} || seen['{{ $panel['key'] }}'])) ? @js($item['thumb'] ?? $item['image']) : null"
                                                @endif
                                                alt="{{ $item['title'] }}"
                                                class="absolute inset-0 h-full w-full object-cover scale-105"
                                                loading="lazy"
                                                decoding="async"
                                            >
                                        @else
                                            <div class="absolute inset-0 bg-gradient-to-br from-forest to-forest-light"></div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal/85 via-charcoal/35 to-transparent"></div>
                                        <div class="absolute inset-x-0 bottom-0 flex flex-col items-start p-7 pb-9">
                                            <p class="text-[0.75rem] tracking-[0.18em] uppercase text-white/80">{{ $item['kicker'] }}</p>
                                            <h3 class="font-display text-3xl md:text-4xl text-white leading-tight mt-1 [text-shadow:0_1px_18px_rgb(0_0_0_/_0.35)]">{{ $item['title'] }}</h3>
                                            @if($item['meta'])
                                                <p class="mt-2 text-[0.75rem] tracking-[0.16em] uppercase text-white/75">{{ $item['meta'] }}</p>
                                            @endif
                                            @if($item['teaser'])
                                                <p class="mt-2 text-base text-white/90 leading-relaxed line-clamp-3">{{ $item['teaser'] }}</p>
                                            @endif
                                            <span class="destination-slide__cta mt-5">Explore</span>
                                        </div>
                                    </a>
                                @endforeach

                                @if($slideCount > 1)
                                    <div class="absolute top-4 right-4 z-10 flex gap-2">
                                        <button type="button" class="country-panel__nav" @click.prevent="prev()" aria-label="Previous destination">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                                        </button>
                                        <button type="button" class="country-panel__nav" @click.prevent="next()" aria-label="Next destination">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col min-h-0 pt-1">
                                <div class="flex flex-wrap items-baseline justify-between gap-3 mb-6">
                                    <div>
                                        <p class="text-[0.75rem] tracking-[0.18em] uppercase text-muted">Destinations</p>
                                        <h3 class="font-display text-3xl text-charcoal mt-1">{{ $panel['label'] }}</h3>
                                    </div>
                                    <a href="{{ $panel['href'] }}" class="home-text-link">{{ $panel['explore'] }} →</a>
                                </div>

                                <div class="flex flex-col gap-4">
                                    @foreach($items as $dIndex => $item)
                                        <a
                                            href="{{ $item['href'] }}"
                                            class="destination-pick group"
                                            :class="index === {{ $dIndex }} ? 'is-active' : ''"
                                            @mouseenter="go({{ $dIndex }})"
                                            @focus="go({{ $dIndex }})"
                                        >
                                            <div class="destination-pick__media">
                                                @if($item['thumb'])
                                                    <img
                                                        @if($loop->parent->first)
                                                            src="{{ $item['thumb'] }}"
                                                        @else
                                                            x-bind:src="seen['{{ $panel['key'] }}'] ? @js($item['thumb']) : null"
                                                        @endif
                                                        alt=""
                                                        loading="lazy"
                                                        decoding="async"
                                                    >
                                                @endif
                                            </div>
                                            <div class="destination-pick__body min-w-0 flex-1">
                                                <h4 class="font-display text-2xl text-charcoal group-hover:text-forest transition">{{ $item['title'] }}</h4>
                                                @if($item['meta'])
                                                    <p class="mt-1.5 text-[0.75rem] tracking-[0.16em] uppercase text-muted">{{ $item['meta'] }}</p>
                                                @endif
                                                @if($item['teaser'])
                                                    <p class="mt-2 text-base text-muted leading-relaxed line-clamp-2">{{ $item['teaser'] }}</p>
                                                @endif
                                                <span class="destination-pick__cta">Explore</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex justify-center reveal">
            <a href="{{ route('destinations.index') }}" class="btn-outline-dark">All destinations</a>
        </div>
    </div>
</section>

{{-- 5. Experiences — vertical portrait strip with auto-scroll --}}
<section class="surface surface--white relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">{{ $experiencesEyebrow }}</p>
            <h2 class="home-section-title">{{ $experiencesHeading }}</h2>
            @if($experiencesIntro)
                <p class="home-section-lead mx-auto max-w-2xl">{{ $experiencesIntro }}</p>
            @endif
        </div>
    </div>

    @if($experiences->isNotEmpty())
        <div class="experience-scroller-wrap mt-2" data-card-scroller-wrap>
            <div
                class="experience-scroller"
                data-card-scroller
                data-pause="2000"
                aria-label="Experiences"
            >
                <div class="experience-scroller__track" data-card-scroller-track>
                    @foreach($experiences as $experience)
                        <a
                            href="{{ route('experiences.show', $experience) }}"
                            class="destination-country-card experience-scroller__card group"
                            data-card-scroller-item
                        >
                            <div class="destination-country-card__media">
                                @if($experience->coverUrl())
                                    <img src="{{ $experience->coverThumbUrl() ?: $experience->coverUrl() }}" alt="{{ $experience->name }}" loading="lazy" decoding="async">
                                @endif
                            </div>
                            <div class="destination-country-card__copy">
                                <h3 class="font-display text-white">{{ $experience->name }}</h3>
                                @if($experience->teaser)
                                    <p class="mt-2 line-clamp-2 text-white/80">{{ $experience->teaser }}</p>
                                @endif
                                <span class="destination-country-card__cta">Explore</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="experience-scroller__controls">
                <button
                    type="button"
                    class="experience-scroller__btn"
                    data-card-scroller-prev
                    aria-label="Previous experiences"
                >
                    <svg class="experience-scroller__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    type="button"
                    class="experience-scroller__btn"
                    data-card-scroller-next
                    aria-label="Next experiences"
                >
                    <svg class="experience-scroller__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-5 lg:px-8 mt-10 text-center reveal">
            <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
        </div>
    @endif
</section>

{{-- 6. Specialist Journeys --}}
@if($specialists->isNotEmpty())
<section class="specialist-stage relative isolate overflow-hidden py-16 text-sand lg:py-24">
    @if(!empty($specialistsBg))
        <div class="specialist-stage__media" aria-hidden="true">
            <img src="{{ $specialistsBg }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="specialist-stage__veil" aria-hidden="true"></div>
    <div class="relative z-10 mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl mb-12">
            <p class="section-eyebrow text-sand/75">{{ $specialistsEyebrow }}</p>
            <h2 class="home-section-title text-white">{{ $specialistsHeading }}</h2>
            @if($specialistsIntro)
                <p class="home-section-lead text-sand/85">{{ $specialistsIntro }}</p>
            @endif
        </div>

        <div class="specialist-mosaic reveal-stagger">
            @foreach($specialists as $specialist)
                <a href="{{ route('specialist.show', $specialist) }}" class="specialist-panel group specialist-panel--{{ $loop->iteration }}">
                    <div class="specialist-panel__frame">
                        <div class="specialist-panel__media">
                            @if($specialist->coverUrl())
                                <img src="{{ $specialist->coverThumbUrl() ?: $specialist->coverUrl() }}" alt="{{ $specialist->name }}" loading="lazy" decoding="async">
                            @endif
                        </div>
                        <div class="specialist-panel__copy">
                            @if($specialist->subtitle)
                                <p class="text-[0.75rem] tracking-[0.2em] uppercase text-sand/85">{{ $specialist->subtitle }}</p>
                            @endif
                            <h3 class="font-display text-white mt-2">{{ $specialist->name }}</h3>
                            @if($specialist->teaser)
                                <p class="mt-3 max-w-md text-white/85 leading-relaxed">{{ $specialist->teaser }}</p>
                            @endif
                            <span class="specialist-panel__cta">Explore this journey</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10 reveal">
            <a href="{{ route('specialist.index') }}" class="btn-outline">All specialist journeys</a>
        </div>
    </div>
</section>
@endif

{{-- 7. Signature Journeys — intro + vertical card rail --}}
<section class="signature-rail surface surface--beige relative pt-16 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
    <div
        class="signature-rail__layout mx-auto max-w-7xl"
        data-card-scroller-wrap
    >
        <div class="signature-rail__intro reveal px-5 lg:px-8">
            <p class="section-eyebrow">{{ $featuredEyebrow }}</p>
            <h2 class="home-section-title">{{ $featuredHeading }}</h2>
            @if($featuredIntro)
                <p class="home-section-lead max-w-md">{{ $featuredIntro }}</p>
            @endif

            @if($journeys->count() > 1)
                <div class="signature-rail__controls mt-8 lg:mt-auto pt-6">
                    <button
                        type="button"
                        class="signature-rail__btn"
                        data-card-scroller-prev
                        aria-label="Previous journeys"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button
                        type="button"
                        class="signature-rail__btn"
                        data-card-scroller-next
                        aria-label="Next journeys"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif

            <div class="mt-8 hidden lg:block">
                <a href="{{ route('journeys.index') }}" class="btn-outline-dark">All journeys</a>
            </div>
        </div>

        @if($journeys->isNotEmpty())
            <div
                class="signature-rail__scroller"
                data-card-scroller
                data-pause="2000"
                aria-label="Signature journeys"
            >
                <div class="signature-rail__track" data-card-scroller-track>
                    @foreach($journeys as $journey)
                        <x-journey-card :journey="$journey" variant="signature" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mx-auto max-w-7xl px-5 lg:px-8 mt-10 lg:hidden reveal">
        <a href="{{ route('journeys.index') }}" class="btn-outline-dark">All journeys</a>
    </div>
</section>

{{-- 8. Why Pearl Pulse --}}
<section class="surface surface--white relative py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Why Pearl Pulse</p>
            <h2 class="home-section-title">What sets a private journey apart</h2>
            <p class="home-section-lead mx-auto max-w-2xl">Five promises we keep on every itinerary — so the wild feels close, and the planning never gets in the way.</p>
        </div>
        <div class="feature-strip reveal-stagger">
            @foreach($pillars as $index => $pillar)
                <div class="feature-strip__item">
                    <span class="feature-strip__mark">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="font-display text-charcoal">{{ $pillar['title'] }}</h3>
                    @if(!empty($pillar['text']))
                        <p class="mt-3 text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. Our People --}}
@if($team->isNotEmpty())
<section class="surface surface--beige relative py-12 lg:py-16 overflow-hidden">
    <div class="mx-auto max-w-5xl px-5 lg:px-8">
        <div class="reveal text-center mb-8">
            <p class="section-eyebrow">Our people</p>
            <h2 class="home-section-title">Meet the people who will look after you</h2>
        </div>
        <div class="people-row reveal-stagger">
            @foreach($team as $member)
                <div class="people-row__person">
                    <div class="people-row__portrait">
                        @if($member->coverUrl())
                            <img src="{{ $member->coverThumbUrl() ?: $member->coverUrl() }}" alt="{{ $member->name }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <p class="mt-3 font-display text-charcoal">{{ $member->name }}</p>
                    @if($member->role)
                        <p class="mt-0.5 text-[0.7rem] tracking-[0.14em] uppercase text-muted">{{ $member->role }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-8 text-center reveal">
            <a href="{{ route('our-people') }}" class="btn-outline-dark">Meet our team</a>
        </div>
    </div>
</section>
@endif

{{-- 10. Conservation --}}
<section class="reason-stage">
    @if($reasonImage)
        <div class="reason-stage__media" aria-hidden="true">
            <img src="{{ $reasonImage }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="reason-stage__veil" aria-hidden="true"></div>
    <div class="reason-stage__copy reveal">
        <p class="text-[0.8rem] tracking-[0.22em] uppercase text-sand/60 mb-3">Conservation</p>
        <h2 class="font-display text-white leading-tight">Travel with a reason</h2>
        <p class="mt-5 text-sand/85 leading-relaxed max-w-lg">Permits fund protection. Local guides and camps create employment. Low-impact routing keeps wild places wild. Every journey can leave more than footprints.</p>
        <div class="mt-8">
            <a href="{{ route('travel-with-a-reason') }}" class="btn-outline">See how we give back</a>
        </div>
    </div>
</section>

{{-- 11. True Pulse --}}
@if($pulseItems->isNotEmpty())
<section class="surface surface--white relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow">True Pulse</p>
                <h2 class="home-section-title">Africa through the eyes of our travellers</h2>
            </div>
            <a href="{{ route('true-pulse') }}" class="home-text-link">Explore True Pulse →</a>
        </div>
        <div class="pulse-mosaic reveal-stagger">
            @foreach($pulseItems as $item)
                <figure>
                    @if($item->coverUrl())
                        <img src="{{ $item->coverThumbUrl() ?: $item->coverUrl() }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                    @endif
                    @if($item->caption || $item->title)
                        <figcaption>{{ $item->caption ?: $item->title }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 12. Selected stays --}}
@if($stays->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Selected stays</p>
            <h2 class="home-section-title">Places we have selected</h2>
            <p class="home-section-lead mx-auto max-w-2xl">For their location, character, and ability to complement your journey. We do not own these lodges.</p>
        </div>
        <div class="editorial-rail reveal-stagger">
            @foreach($stays as $stay)
                <x-stay-card :stay="$stay" />
            @endforeach
        </div>
        <div class="mt-10 text-center reveal">
            <a href="{{ route('stays.index') }}" class="btn-outline-dark">All selected stays</a>
        </div>
    </div>
</section>
@endif

{{-- 13. Guest Voices --}}
@if($reviews->isNotEmpty())
<section class="surface surface--brown relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-12">
            <div>
                <p class="section-eyebrow">Guest voices</p>
                <h2 class="home-section-title">What travellers say</h2>
            </div>
            <a href="{{ route('reviews.index') }}" class="home-text-link">Read all guest reviews →</a>
        </div>
        <div class="guest-voices reveal-stagger">
            @foreach($reviews as $review)
                <blockquote class="guest-voices__card">
                    <p class="font-display text-charcoal leading-snug">“{{ $review->quote }}”</p>
                    <footer class="mt-6 pt-4 border-t border-forest/15 text-base text-muted">
                        <span class="text-charcoal font-medium">{{ $review->guest_name }}</span>
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

        <x-review-platform-links :links="$reviewLinks ?? []" class="mt-10" />
    </div>
</section>
@endif

{{-- 14. From the Field --}}
@if($fieldNotes->isNotEmpty())
<section class="surface surface--white py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-eyebrow">From the Field</p>
                <h2 class="home-section-title">Notes from East Africa</h2>
            </div>
            <a href="{{ route('insiders.index', ['type' => 'field']) }}" class="home-text-link">All field notes →</a>
        </div>
        <div class="field-notes reveal-stagger">
            @foreach($fieldNotes as $article)
                <a href="{{ route('insiders.show', $article) }}">
                    <p class="text-[0.75rem] tracking-[0.16em] uppercase text-muted mb-2">{{ $article->typeLabel() }}</p>
                    <h3 class="font-display text-charcoal">{{ $article->title }}</h3>
                    @if($article->excerpt)
                        <p class="mt-3 text-base text-muted leading-relaxed line-clamp-3">{{ $article->excerpt }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 15. Quick FAQs --}}
@if($faqs->isNotEmpty())
<section class="surface surface--beige relative py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <div class="reveal text-center mb-10">
            <p class="section-eyebrow">Quick FAQs</p>
            <h2 class="home-section-title">Questions guests ask first</h2>
        </div>
        <div class="faq-list" x-data="{ open: 0 }">
            @foreach($faqs as $i => $faq)
                <div class="faq-item">
                    <button
                        type="button"
                        class="faq-item__trigger"
                        @click="open = open === {{ $i }} ? null : {{ $i }}"
                        :aria-expanded="(open === {{ $i }}).toString()"
                    >
                        <span>{{ $faq->question }}</span>
                        <span class="faq-item__icon" x-text="open === {{ $i }} ? '−' : '+'" aria-hidden="true"></span>
                    </button>
                    <div class="faq-item__panel" x-show="open === {{ $i }}" x-cloak>
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10 text-center reveal">
            <a href="{{ route('faqs.index') }}" class="btn-outline-dark">View all FAQs</a>
        </div>
    </div>
</section>
@endif

{{-- 16. Final CTA --}}
<x-page-cta
    :heading="$homeCtaHeading"
    :text="$homeCtaText"
    button="Journey Finder"
    :href="route('journeys.finder')"
    :secondary="route('plan')"
    :secondary-label="$homeCtaButton"
>
    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn-outline shadow-[0_8px_30px_rgb(0_0_0_/_0.25)] bg-transparent">WhatsApp</a>
    @endif
</x-page-cta>
@endsection
