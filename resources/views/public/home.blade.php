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
            <div class="hero-slide__shade absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-transparent"></div>
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
<section class="surface surface--beige home-intro-section">
    <div class="home-intro reveal">
        <div class="home-intro__still">
            @if($homeIntroImage)
                <img src="{{ $homeIntroImage }}" alt="" loading="lazy" decoding="async">
            @endif
        </div>
        <div class="home-intro__copy">
            @if($homeIntroEyebrow)
                <p class="section-eyebrow">{{ $homeIntroEyebrow }}</p>
            @endif
            @if($homeIntroHeading)
                <h2 class="home-section-title home-intro__title">{{ $homeIntroHeading }}</h2>
            @endif
            @if($homeIntroBody)
                <p class="home-section-lead home-intro__lead whitespace-pre-line">{{ $homeIntroBody }}</p>
            @endif
            <a href="{{ route('about') }}" class="btn-outline-dark home-intro__cta">Discover Pearl Pulse</a>
        </div>
    </div>
</section>
@endif

{{-- 4. Destinations — country tabs + feature panel (client original) --}}
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-5xl text-center max-lg:mx-0 max-lg:text-left">
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
                                        <h3 class="font-display text-3xl text-white leading-tight">{{ $item['title'] }}</h3>
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
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-black/20"></div>
                                        <div class="destination-slide__copy destination-slide__copy--panel">
                                            <h3 class="font-display text-3xl md:text-4xl text-white leading-tight [text-shadow:0_1px_18px_rgb(0_0_0_/_0.35)]">{{ $item['title'] }}</h3>
                                            <span class="destination-slide__cta">Explore</span>
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
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-7xl text-center mb-12">
            <h2 class="home-section-title home-experiences__title">{{ $experiencesHeading }}</h2>
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
                data-pause="5000"
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
        class="signature-rail__layout"
        data-card-scroller-wrap
    >
        <div class="signature-rail__intro reveal">
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
                data-pause="5000"
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

    <div class="signature-rail__mobile-cta mt-10 lg:hidden reveal">
        <a href="{{ route('journeys.index') }}" class="btn-outline-dark">All journeys</a>
    </div>
</section>

{{-- 8. Why Pearl Pulse --}}
@php
    $pillarIcons = [
        // Private by design — private group
        '<circle cx="9" cy="8.25" r="3.25"/><path d="M3.5 19.25c.55-3.1 2.75-4.75 5.5-4.75s4.95 1.65 5.5 4.75"/><path d="M15.5 5.4a3.25 3.25 0 0 1 0 5.7M17.75 14.9c1.55.65 2.5 2 2.85 4.1"/>',
        // Local knowledge — compass
        '<circle cx="12" cy="12" r="8.5"/><path d="m15.75 8.25-2.25 5-5 2.25 2.25-5z"/><circle cx="12" cy="12" r="0.75" fill="currentColor" stroke="none"/>',
        // Seamless logistics — route
        '<circle cx="6" cy="19" r="2.25"/><circle cx="18" cy="5" r="2.25"/><path d="M8.25 19H15a3 3 0 0 0 0-6h-6a3 3 0 0 1 0-6h6.75"/>',
        // Conservation-minded — leaf
        '<path d="M5.5 18.5C5.5 10.5 11.5 4.5 19.5 4.5c0 8-5.5 14-14 14Z"/><path d="M5.5 18.5c2.5-5.5 6-9 10.5-11"/>',
        // Honest counsel — conversation
        '<path d="M20 11.5a7.5 7.5 0 0 1-7.5 7.5c-1.25 0-2.45-.3-3.5-.85L4 20l1.1-3.35A7.5 7.5 0 1 1 20 11.5Z"/><path d="M8.25 10.75h7.5M8.25 13.75h4.5"/>',
    ];
@endphp
<section class="why-stage relative isolate overflow-hidden py-20 lg:py-28">
    @if(!empty($whyImage))
        <div class="why-stage__media" aria-hidden="true">
            <img src="{{ $whyImage }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="why-stage__veil" aria-hidden="true"></div>
    <div class="relative z-10">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <div class="reveal mx-auto max-w-3xl text-center mb-12">
                <p class="section-eyebrow">Why Pearl Pulse</p>
                <h2 class="home-section-title">What sets a private journey apart</h2>
                <p class="home-section-lead mx-auto max-w-2xl">Five promises we keep on every itinerary — so the wild feels close, and the planning never gets in the way.</p>
            </div>
        </div>
        <div class="why-stage__strip px-3 sm:px-4 lg:px-5">
            <div class="feature-strip reveal-stagger">
                @foreach($pillars as $index => $pillar)
                    <div class="feature-strip__item">
                        <span class="feature-strip__mark" aria-hidden="true">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $pillarIcons[$index % count($pillarIcons)] !!}</svg>
                        </span>
                        <h3 class="font-display text-charcoal">{{ $pillar['title'] }}</h3>
                        @if(!empty($pillar['text']))
                            <p class="mt-3 text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 9. Our People --}}
@if($team->isNotEmpty())
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <h2 class="home-section-title">Meet the people who will look after you</h2>
        </div>
        <div class="people-grid reveal-stagger">
            @foreach($team as $member)
                <article class="people-card">
                    <div class="people-card__media">
                        @if($member->coverUrl())
                            <img src="{{ $member->coverUrl() }}" alt="{{ $member->name }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="people-card__copy">
                        <h3 class="people-card__title">{{ $member->name }}</h3>
                        @if($member->role)
                            <p class="people-card__role">{{ $member->role }}</p>
                        @endif
                        @if($member->bio)
                            <p class="people-card__bio">{{ $member->bio }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-12 text-center reveal">
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
<section class="surface surface--beige relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-6 mb-10">
            <div class="max-w-2xl">
                <p class="section-eyebrow">True Pulse</p>
                <h2 class="home-section-title">Africa through the eyes of our travellers</h2>
            </div>
            <div class="max-w-sm">
                <p class="text-muted leading-relaxed">Guest photographs, reels, and field notes — shared with permission, never staged for the brochure.</p>
                <a href="{{ route('true-pulse') }}" class="home-text-link mt-4">Explore True Pulse →</a>
            </div>
        </div>
        <div class="pulse-mosaic reveal-stagger">
            @foreach($pulseItems as $item)
                <a href="{{ route('true-pulse') }}" class="pulse-mosaic__link" aria-label="{{ $item->title ?: 'Explore True Pulse' }}">
                    <figure>
                        @if($item->coverUrl())
                            <img src="{{ $item->coverThumbUrl() ?: $item->coverUrl() }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                        @endif
                        @if($item->type === 'reel' || $item->videoPlaybackUrl())
                            <span class="pulse-mosaic__play" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </span>
                        @endif
                        <span class="pulse-mosaic__arrow" aria-hidden="true">↗</span>
                        <figcaption>
                            <span class="pulse-mosaic__meta">
                                Guest {{ $item->type ?: 'photo' }}@if($item->guest_name) · {{ $item->guest_name }}@endif
                            </span>
                            @if($item->title)
                                <span class="pulse-mosaic__title">{{ $item->title }}</span>
                            @endif
                            @if($item->caption)
                                <span class="pulse-mosaic__text">{{ $item->caption }}</span>
                            @endif
                        </figcaption>
                    </figure>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 12. Selected stays — by country --}}
@if($stayCountries->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <h2 class="home-section-title">Places we have selected</h2>
            <p class="home-section-lead mx-auto max-w-2xl">Lodges and camps chosen by country for location, character, and how they sit in the day. We do not own these stays.</p>
        </div>
        <div class="stay-country-grid reveal-stagger">
            @foreach($stayCountries as $item)
                <a href="{{ $item['href'] }}" class="destination-country-card group">
                    <div class="destination-country-card__media">
                        @if($item['image'])
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="destination-country-card__copy">
                        <h3 class="font-display text-white">{{ $item['name'] }}</h3>
                        @if(!empty($item['teaser']))
                            <p class="mt-2 line-clamp-2 text-white/80">{{ $item['teaser'] }}</p>
                        @endif
                        <span class="destination-country-card__cta">Explore</span>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-center reveal">
            <a href="{{ route('stays.index') }}" class="btn-outline-dark">All preferred lodges</a>
        </div>
    </div>
</section>
@endif

{{-- 13. Guest Voices — actual Google & Tripadvisor reviews when connected --}}
@php
    $platformReviewList = $platformReviews['reviews'] ?? [];
    $hasPlatformReviews = $platformReviewList !== [];
@endphp
@if($hasPlatformReviews || $reviews->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow">Guest voices</p>
                <h2 class="home-section-title">What travellers say</h2>
            </div>
            <a href="{{ route('reviews.index') }}" class="home-text-link">Read all guest reviews →</a>
        </div>

        @if($hasPlatformReviews)
            <div class="guest-voices__platforms reveal">
                @foreach(['google' => 'Google', 'tripadvisor' => 'Tripadvisor'] as $key => $label)
                    @if(!empty($platformReviews[$key]))
                        @php $platform = $platformReviews[$key]; @endphp
                        <a href="{{ $platform['url'] }}" target="_blank" rel="noopener noreferrer" class="guest-voices__platform">
                            <x-platform-logo :platform="$label" :size="26" />
                            <span class="guest-voices__platform-name">{{ $label }}</span>
                            @if($platform['rating'] !== null)
                                <x-review-stars :rating="$platform['rating']" />
                                <span class="guest-voices__platform-rating">{{ number_format($platform['rating'], 1) }}</span>
                            @endif
                            @if($platform['count'] !== null)
                                <span class="guest-voices__platform-count">{{ number_format($platform['count']) }} reviews</span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="guest-voices reveal-stagger">
                @foreach($platformReviewList as $review)
                    <blockquote class="guest-voices__card guest-voices__card--platform">
                        <div class="guest-voices__card-head">
                            <span class="guest-voices__source">
                                <x-platform-logo :platform="$review['platform']" :size="20" />
                                <span>{{ $review['platform'] }}</span>
                            </span>
                            <x-review-stars :rating="$review['rating']" />
                        </div>
                        @if($review['title'])
                            <h3 class="guest-voices__card-title">{{ $review['title'] }}</h3>
                        @endif
                        <p class="guest-voices__quote line-clamp-6">“{{ $review['text'] }}”</p>
                        <footer class="guest-voices__card-foot">
                            @if($review['avatar'])
                                <img src="{{ $review['avatar'] }}" alt="" class="guest-voices__avatar" loading="lazy" decoding="async" referrerpolicy="no-referrer">
                            @else
                                <span class="guest-voices__avatar guest-voices__avatar--initial" aria-hidden="true">{{ strtoupper(substr($review['author'], 0, 1)) }}</span>
                            @endif
                            <span class="guest-voices__author">
                                <span class="text-charcoal font-medium">{{ $review['author'] }}</span>
                                @if($review['date'])
                                    <span class="guest-voices__date">{{ $review['date'] }}</span>
                                @endif
                            </span>
                            @if($review['url'])
                                <a href="{{ $review['url'] }}" target="_blank" rel="noopener noreferrer" class="guest-voices__read">Read on {{ $review['platform'] }} ↗</a>
                            @endif
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        @else
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
        @endif

        <x-review-platform-links :links="$reviewLinks ?? []" class="mt-10" />
    </div>
</section>
@endif

{{-- 14. Quick FAQs --}}
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

{{-- 15. Final CTA --}}
<x-page-cta
    :heading="$homeCtaHeading"
    :text="$homeCtaText"
    button="Journey Finder"
    :href="route('journeys.finder')"
>
    <a href="{{ route('contact') }}" class="btn-outline shadow-[0_8px_30px_rgb(0_0_0_/_0.25)]">Talk to a specialist</a>
</x-page-cta>
@endsection
