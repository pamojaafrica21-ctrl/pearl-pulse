@extends('layouts.public')

@section('title', config('app.name').' — Private journeys into Africa’s wild heart')
@section('meta_description', $heroSlides[0]['tagline'] ?? 'Private safari journeys across East Africa')

@section('content')
@php
    $slideCount = count($heroSlides);
    $heroDuration = 8000;
@endphp

<section
    class="home-hero relative flex flex-col justify-end overflow-hidden bg-forest"
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
                    preload="{{ $i === 0 ? 'metadata' : 'none' }}"
                    poster="{{ $slide['image_url'] }}"
                    data-parallax="0.08"
                    x-bind:autoplay="index === {{ $i }}"
                    @if($i === 0) autoplay @endif
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
            <div class="absolute inset-0 bg-gradient-to-t from-charcoal/45 via-charcoal/20 to-black/15"></div>
        </div>
    @endforeach

    <div class="home-hero__content relative z-10 mx-auto w-full max-w-7xl px-5 lg:px-8">
        @foreach($heroSlides as $i => $slide)
            <div x-show="index === {{ $i }}" @if($i !== 0) x-cloak @endif>
                @if($slide['label'])
                    <p class="text-[11px] tracking-[0.22em] uppercase text-white/70 fade-up">{{ $slide['label'] }}</p>
                @endif
                <h1 class="home-hero__title font-display text-white leading-[0.95] max-w-4xl fade-up mt-3 sm:mt-4" style="animation-delay: 0.08s">
                    {{ $slide['headline'] }}
                </h1>
                @if($slide['tagline'])
                    <p class="home-hero__tagline mt-4 sm:mt-6 max-w-xl text-white/85 font-light leading-relaxed fade-up" style="animation-delay: 0.18s">
                        {{ $slide['tagline'] }}
                    </p>
                @endif
            </div>
        @endforeach

        <div class="mt-8 sm:mt-10 flex flex-wrap gap-4 fade-up" style="animation-delay: 0.28s">
            <a href="{{ route('plan') }}" class="btn-outline">Plan your journey</a>
            <a href="{{ route('journeys.index') }}" class="text-sm tracking-[0.14em] uppercase text-white/80 hover:text-white self-center transition">
                Explore journeys
            </a>
        </div>

        @if($slideCount > 1)
            @php
                $nextLabels = collect($heroSlides)->map(fn ($s) => $s['label'] ?: $s['headline'])->values()->all();
            @endphp
            <div class="mt-10 sm:mt-14 flex flex-wrap items-end justify-between gap-6 fade-up" style="animation-delay: 0.4s">
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

<div class="trust-band surface surface--white">
    <div class="trust-band__row mx-auto max-w-7xl px-5 lg:px-8">
        <span>Gorilla tracking</span>
        <span>Great migration</span>
        <span>Uganda · Rwanda · Kenya · Tanzania</span>
        <span>Private journeys</span>
    </div>
</div>

@if($homeIntroHeading || $homeIntroBody)
<section class="surface surface--white py-14 lg:py-20">
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
            <a href="{{ route('about') }}" class="btn-outline-dark">Discover Pearl Pulse</a>
        </div>
    </div>
</section>
@endif

<section class="surface surface--beige relative py-16 lg:py-20 overflow-hidden">
    <x-section-edge placement="top" variant="wave" class="text-beige" />
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-5xl text-center max-lg:mx-0 max-lg:text-left">
            <div class="mb-4 flex justify-center max-lg:justify-start">
                <x-path-accent />
            </div>
            <p class="section-eyebrow">{{ $destinationsEyebrow }}</p>
            <h2 class="font-display text-[clamp(1.85rem,4.6vw,3.15rem)] text-charcoal whitespace-nowrap">{{ $destinationsHeading }}</h2>
            @if($destinationsIntro)
                <p class="mt-4 text-muted leading-relaxed max-lg:max-w-xl">{{ $destinationsIntro }}</p>
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
                                        <p class="text-[11px] tracking-[0.18em] uppercase text-white/80">{{ $item['kicker'] }}</p>
                                        <h3 class="font-display text-3xl text-white leading-tight mt-1">{{ $item['title'] }}</h3>
                                        @if($item['teaser'])
                                            <p class="mt-2 text-sm text-white/90 leading-relaxed line-clamp-3">{{ $item['teaser'] }}</p>
                                        @endif
                                        <span class="destination-slide__cta" aria-hidden="true">Explore</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-5 lg:hidden">
                            <a href="{{ $panel['href'] }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">
                                {{ $panel['explore'] }}
                            </a>
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
                                            <p class="text-[11px] tracking-[0.18em] uppercase text-white/80">{{ $item['kicker'] }}</p>
                                            <h3 class="font-display text-3xl md:text-4xl text-white leading-tight mt-1 [text-shadow:0_1px_18px_rgb(0_0_0_/_0.35)]">{{ $item['title'] }}</h3>
                                            @if($item['meta'])
                                                <p class="mt-2 text-[11px] tracking-[0.16em] uppercase text-white/75">{{ $item['meta'] }}</p>
                                            @endif
                                            @if($item['teaser'])
                                                <p class="mt-2 text-sm text-white/90 leading-relaxed line-clamp-3">{{ $item['teaser'] }}</p>
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
                                        <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Destinations</p>
                                        <h3 class="font-display text-3xl text-charcoal mt-1">{{ $panel['label'] }}</h3>
                                    </div>
                                    <a href="{{ $panel['href'] }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">
                                        {{ $panel['explore'] }}
                                    </a>
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
                                                    <p class="mt-1.5 text-[11px] tracking-[0.16em] uppercase text-muted">{{ $item['meta'] }}</p>
                                                @endif
                                                @if($item['teaser'])
                                                    <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $item['teaser'] }}</p>
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
    <x-section-edge variant="ridge" class="text-white" />
</section>

<section class="signature-rail surface surface--white relative pt-16 pb-20 lg:pt-20 lg:pb-28 overflow-hidden">
    <div
        class="signature-rail__layout mx-auto max-w-7xl"
        data-card-scroller-wrap
    >
        <div class="signature-rail__intro reveal px-5 lg:px-8">
            <p class="section-eyebrow">{{ $featuredEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal leading-tight">{{ $featuredHeading }}</h2>
            @if($featuredIntro)
                <p class="mt-4 text-muted leading-relaxed max-w-md">{{ $featuredIntro }}</p>
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
                data-manual
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

    <x-section-edge variant="ridge" class="text-forest" />
</section>


<section class="surface surface--green text-sand py-20 lg:py-24 overflow-hidden relative surface-nature">
    <div class="relative mx-auto max-w-3xl px-5 lg:px-8 reveal text-center">
        <p class="text-[11px] tracking-[0.22em] uppercase text-sand/50 mb-3">Journey Finder</p>
        <h2 class="font-display text-4xl md:text-5xl text-white">Not sure where to begin?</h2>
        <p class="mt-4 text-sand/75 leading-relaxed">Find the Pearl Pulse journey that fits the way you want to experience Africa.</p>
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('journeys.finder') }}" class="btn-outline">Find your journey</a>
            <button type="button" @click="$dispatch('open-journey-search')" class="text-sm tracking-[0.14em] uppercase text-sand/80 hover:text-white self-center transition">Quick search</button>
        </div>
    </div>
    <x-section-edge variant="ridge" class="text-beige" />
</section>
<section class="surface surface--beige relative pt-24 pb-20 lg:pt-32 lg:pb-28 overflow-hidden">
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
        <div class="experience-scroller-wrap mt-12" data-card-scroller-wrap>
            <div
                class="experience-scroller"
                data-card-scroller
                data-pause="3200"
            >
                <div class="experience-scroller__track" data-card-scroller-track>
                    @foreach($experiences as $experience)
                        <a
                            href="{{ route('experiences.show', $experience) }}"
                            class="experience-tile group relative block overflow-hidden min-h-[20rem] w-[78vw] sm:w-[42vw] lg:w-[22rem] xl:w-[26rem] shrink-0 bg-forest"
                            data-card-scroller-item
                        >
                            @if($experience->coverThumbUrl() || $experience->coverUrl())
                                <img src="{{ $experience->coverThumbUrl() ?: $experience->coverUrl() }}" alt="{{ $experience->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy" decoding="async">
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
            </div>

            <div class="experience-scroller__controls">
                <button
                    type="button"
                    class="experience-scroller__btn"
                    data-card-scroller-prev
                    aria-label="Previous experiences"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    type="button"
                    class="experience-scroller__btn"
                    data-card-scroller-next
                    aria-label="Next experiences"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    @endif

    <div class="mx-auto max-w-7xl px-5 lg:px-8 mt-12 flex justify-center reveal">
        <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
    </div>
    <x-section-edge variant="ridge" class="text-white" />
</section>


@if(count($homePillars))
<section class="surface surface--white relative py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Why Pearl Pulse</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Real differentiators</h2>
        </div>
        <div class="feature-strip reveal-stagger">
            @foreach($homePillars as $index => $pillar)
                <div class="feature-strip__item">
                    <span class="feature-strip__mark">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="font-display text-2xl text-charcoal">{{ $pillar['title'] }}</h3>
                    <p class="mt-3 text-sm text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($team->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal text-center mb-14">
            <p class="section-eyebrow">Our people</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Your journey. Our people.</h2>
            <p class="mt-4 text-muted max-w-xl mx-auto">The guides and planners behind Pearl Pulse — visible, local, and with you from the first conversation.</p>
        </div>
        <div class="people-row reveal-stagger">
            @foreach($team as $member)
                <div class="people-row__person">
                    <div class="people-row__portrait">
                        @if($member->coverUrl())
                            <img src="{{ $member->coverThumbUrl() ?: $member->coverUrl() }}" alt="{{ $member->name }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <p class="mt-4 font-display text-xl text-charcoal">{{ $member->name }}</p>
                    @if($member->role)
                        <p class="mt-1 text-[11px] tracking-[0.16em] uppercase text-muted">{{ $member->role }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-14 text-center reveal">
            <a href="{{ route('our-people') }}" class="btn-outline-dark">Meet our team</a>
        </div>
    </div>
</section>
@endif

<section class="reason-stage">
    @if($reasonImage)
        <div class="reason-stage__media" aria-hidden="true">
            <img src="{{ $reasonImage }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="reason-stage__veil" aria-hidden="true"></div>
    <div class="reason-stage__copy reveal">
        <p class="text-[11px] tracking-[0.22em] uppercase text-sand/55 mb-3">Travel with a reason</p>
        <h2 class="font-display text-4xl md:text-5xl text-white leading-tight">Conservation, community, local people</h2>
        <p class="mt-5 text-sand/80 leading-relaxed max-w-lg">Our journeys connect places and people — so travelling with us can contribute to the land that makes these days possible.</p>
        <div class="mt-8">
            <a href="{{ route('travel-with-a-reason') }}" class="btn-outline">Discover our approach</a>
        </div>
    </div>
</section>

@if($pulseItems->isNotEmpty())
<section class="surface surface--white relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <p class="section-eyebrow">True Pulse</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">Africa through the eyes of our travellers</h2>
            </div>
            <a href="{{ route('true-pulse') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">Explore True Pulse</a>
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

@if($stays->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Selected stays</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Places we have selected</h2>
            <p class="mt-4 text-muted leading-relaxed">For their location, character, and ability to complement your journey. We do not own these lodges.</p>
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

@if($reviews->isNotEmpty())
<section class="surface surface--brown relative py-24 lg:py-32">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-12 reveal">
            <div>
                <p class="section-eyebrow">Guest reviews</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">The journeys our guests remember</h2>
            </div>
            <div class="flex gap-4 text-sm">
                @if(!empty($reviewLinks['google']))
                    <a href="{{ $reviewLinks['google'] }}" class="tracking-[0.12em] uppercase text-forest hover:opacity-70 transition" target="_blank" rel="noopener">Google reviews</a>
                @endif
                @if(!empty($reviewLinks['tripadvisor']))
                    <a href="{{ $reviewLinks['tripadvisor'] }}" class="tracking-[0.12em] uppercase text-forest hover:opacity-70 transition" target="_blank" rel="noopener">Tripadvisor</a>
                @endif
            </div>
        </div>
        <div class="marquee reveal" data-marquee>
            <div class="marquee__track" data-marquee-inner>
                @foreach([false, true] as $isClone)
                    <div class="marquee__group" @if($isClone) aria-hidden="true" @endif>
                        @foreach($reviews as $review)
                            <blockquote class="w-[82vw] shrink-0 sm:w-[28rem] lg:w-[32rem] px-2">
                                <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                                <footer class="mt-6 pt-4 border-t border-forest/15 text-sm text-muted">
                                    <span class="text-charcoal">{{ $review->guest_name }}</span>
                                    @if($review->guest_country)
                                        · {{ $review->guest_country }}
                                    @endif
                                    @if($review->journey)
                                        · <a href="{{ route('journeys.show', $review->journey) }}" class="hover:text-forest transition" @if($isClone) tabindex="-1" @endif>{{ $review->journey->name }}</a>
                                    @endif
                                </footer>
                            </blockquote>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

@if($fieldNotes->isNotEmpty())
<section class="surface surface--white py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mb-10">
            <p class="section-eyebrow">From the Field</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Practical answers from the ground</h2>
        </div>
        <div class="field-notes reveal-stagger">
            @foreach($fieldNotes as $article)
                <a href="{{ route('insiders.show', $article) }}">
                    <p class="text-[11px] tracking-[0.16em] uppercase text-muted mb-2">{{ $article->typeLabel() }}</p>
                    <h3 class="font-display text-2xl text-charcoal">{{ $article->title }}</h3>
                    @if($article->excerpt)
                        <p class="mt-3 text-sm text-muted leading-relaxed line-clamp-3">{{ $article->excerpt }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<x-page-cta :heading="$homeCtaHeading" :text="$homeCtaText" :button="$homeCtaButton">
    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-sm tracking-[0.14em] uppercase text-white/75 hover:text-white transition [text-shadow:0_1px_12px_rgb(0_0_0_/_0.4)]">WhatsApp</a>
    @endif
</x-page-cta>
@endsection
