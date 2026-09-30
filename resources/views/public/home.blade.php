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
    $mobileTrust = array_slice($trustItems, 0, 3);
    $defaultPillars = [
        ['title' => 'Private by design', 'text' => 'Your vehicle, your pace, your group — never shared seat sales.'],
        ['title' => 'Local knowledge', 'text' => 'Uganda-based specialists with deep East Africa relationships.'],
        ['title' => 'Seamless logistics', 'text' => 'Permits, lodges, transfers, and timing handled end to end.'],
        ['title' => 'Conservation-minded', 'text' => 'Travel that supports communities and wild places.'],
        ['title' => 'Honest counsel', 'text' => 'We say no when a trip does not fit — and reshape when it can.'],
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
            <div class="hero-slide__shade absolute inset-0 bg-gradient-to-t from-charcoal/55 via-charcoal/25 to-black/15"></div>
        </div>
    @endforeach

    <div class="relative z-10 flex min-h-[inherit] flex-1 flex-col justify-end">
        <div class="home-hero__content relative mx-auto w-full max-w-7xl px-5 lg:px-8">
            @foreach($heroSlides as $i => $slide)
                <div x-show="index === {{ $i }}" @if($i !== 0) x-cloak @endif>
                    <p class="text-[11px] tracking-[0.22em] uppercase text-white/70 fade-up">{{ $slide['label'] ?: 'Pearl Pulse Safaris' }}</p>
                    <h1 class="home-hero__title font-display text-white leading-[0.95] max-w-4xl fade-up mt-3 sm:mt-4" style="animation-delay: 0.08s">
                        {{ $slide['headline'] ?: "Private journeys into Africa's wild heart" }}
                    </h1>
                    @if($slide['tagline'] || $i === 0)
                        <p class="home-hero__tagline mt-4 sm:mt-6 max-w-xl text-white/85 font-light leading-relaxed fade-up" style="animation-delay: 0.18s">
                            {{ $slide['tagline'] ?: 'Uganda-based specialists. East Africa, designed around you.' }}
                        </p>
                    @endif
                </div>
            @endforeach

            <div class="home-hero__actions fade-up" style="animation-delay: 0.28s">
                <a href="{{ route('plan') }}" class="btn-outline home-hero__btn">Plan Your Journey</a>
                <a href="{{ route('journeys.finder') }}" class="btn-outline home-hero__btn home-hero__btn--ghost">Find Your Journey</a>
            </div>

            @if($slideCount > 1)
                @php
                    $nextLabels = collect($heroSlides)->map(fn ($s) => $s['label'] ?: $s['headline'])->values()->all();
                @endphp
                <div class="home-hero__meta fade-up" style="animation-delay: 0.4s">
                    <div class="home-hero__next">
                        <p class="text-[10px] tracking-[0.2em] uppercase text-white/55 mb-2">Next up</p>
                        <p class="text-sm text-white/90 font-light truncate">
                            <span x-text="{{ Js::from($nextLabels) }}[(index + 1) % count]"></span>
                        </p>
                        <div class="hero-progress mt-3" style="--hero-duration: {{ $heroDuration }}ms">
                            <span :key="index"></span>
                        </div>
                    </div>
                    <div class="home-hero__nav">
                        <button type="button" @click="prev()" class="home-hero__nav-btn" aria-label="Previous slide">‹</button>
                        <button type="button" @click="next()" class="home-hero__nav-btn" aria-label="Next slide">›</button>
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. Trust bar --}}
        <div class="home-hero__trust home-hero__trust--forest relative z-10 mt-auto">
            <div class="home-hero__trust-row mx-auto max-w-7xl">
                @foreach($mobileTrust as $item)
                    <span class="md:hidden">{{ $item['title'] }}</span>
                @endforeach
                @foreach($trustItems as $item)
                    <span class="hidden md:inline{{ strlen($item['title']) > 22 ? ' home-hero__trust-item--wide' : '' }}">{{ $item['title'] }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 3. Short intro --}}
@if($homeIntroHeading || $homeIntroBody)
<section class="surface surface--white py-14 lg:py-20">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 lg:grid-cols-12 lg:gap-14 lg:px-8">
        <div class="reveal lg:col-span-7 @if(!($introImage ?? null)) lg:col-span-12 lg:mx-auto lg:max-w-3xl lg:text-center @endif">
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
        @if(!empty($introImage))
            <div class="reveal hidden overflow-hidden lg:col-span-5 lg:block" style="transition-delay:120ms">
                <div class="aspect-[4/5] overflow-hidden bg-sand">
                    <img src="{{ $introImage }}" alt="" class="h-full w-full object-cover transition duration-[1.4s] ease-out hover:scale-[1.04]" loading="lazy" decoding="async">
                </div>
            </div>
        @endif
    </div>
</section>
@endif

{{-- 4. Destinations — country card grid --}}
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center">
            <p class="section-eyebrow">{{ $destinationsEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $destinationsHeading }}</h2>
            @if($destinationsIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $destinationsIntro }}</p>
            @endif
        </div>

        <div class="destination-country-grid reveal-stagger mt-12">
            @foreach($countries as $country)
                <a href="{{ route('destinations.country', $country) }}" class="destination-country-card group">
                    <div class="destination-country-card__media">
                        @if($country->coverUrl())
                            <img src="{{ $country->coverThumbUrl() ?: $country->coverUrl() }}" alt="" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="destination-country-card__copy">
                        <h3 class="font-display text-3xl md:text-4xl text-white">{{ $country->name }}</h3>
                        <p class="mt-2 line-clamp-2 text-sm text-white/80 leading-relaxed">
                            {{ $country->teaser ?: \Illuminate\Support\Str::limit(strip_tags((string) $country->description), 90) }}
                        </p>
                        <span class="destination-country-card__cta">Explore</span>
                    </div>
                </a>
            @endforeach

            @if($hasMultiCountry)
                <a href="{{ route('journeys.index', ['type' => 'multi']) }}" class="destination-country-card destination-country-card--multi group">
                    <div class="destination-country-card__media destination-country-card__media--pattern" aria-hidden="true"></div>
                    <div class="destination-country-card__copy">
                        <p class="text-[11px] tracking-[0.2em] uppercase text-sand/85">Multi-country</p>
                        <h3 class="font-display text-3xl md:text-4xl text-white mt-2">Around East Africa</h3>
                        <p class="mt-2 text-sm text-white/85 leading-relaxed">Cross borders in one private journey — Uganda, Rwanda, Kenya, Tanzania as one narrative.</p>
                        <span class="destination-country-card__cta">View journeys</span>
                    </div>
                </a>
            @endif
        </div>

        <div class="mt-10 text-center reveal">
            <a href="{{ route('destinations.index') }}" class="btn-outline-dark">All destinations</a>
        </div>
    </div>
</section>

{{-- 5. Experiences — full photo grid --}}
<section class="surface surface--white relative py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">{{ $experiencesEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $experiencesHeading }}</h2>
            @if($experiencesIntro)
                <p class="mt-4 text-muted leading-relaxed">{{ $experiencesIntro }}</p>
            @endif
        </div>

        @if($experiences->isNotEmpty())
            <div class="experience-photo-grid reveal-stagger">
                @foreach($experiences as $experience)
                    <a href="{{ route('experiences.show', $experience) }}" class="experience-tile experience-tile--grid group relative block overflow-hidden aspect-[3/4] bg-forest">
                        @if($experience->coverUrl())
                            <img src="{{ $experience->coverThumbUrl() ?: $experience->coverUrl() }}" alt="{{ $experience->name }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy" decoding="async">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal/90 via-charcoal/25 to-transparent"></div>
                        <div class="tile-copy relative z-10 flex h-full flex-col justify-end p-5">
                            <h3 class="font-display text-xl md:text-2xl text-white">{{ $experience->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10 text-center reveal">
                <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
            </div>
        @endif
    </div>
</section>

{{-- 6. Specialist Journeys --}}
@if($specialists->isNotEmpty())
<section class="surface surface--green text-sand relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-3xl mb-12">
            <p class="section-eyebrow text-sand/70">{{ $specialistsEyebrow }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-white">{{ $specialistsHeading }}</h2>
            @if($specialistsIntro)
                <p class="mt-4 text-sand/80 leading-relaxed">{{ $specialistsIntro }}</p>
            @endif
        </div>

        <div class="specialist-mosaic reveal-stagger">
            @foreach($specialists as $specialist)
                <a href="{{ route('specialist.show', $specialist) }}" class="specialist-panel group specialist-panel--{{ $loop->iteration }}">
                    <div class="specialist-panel__media">
                        @if($specialist->coverUrl())
                            <img src="{{ $specialist->coverThumbUrl() ?: $specialist->coverUrl() }}" alt="{{ $specialist->name }}" loading="lazy" decoding="async">
                        @endif
                    </div>
                    <div class="specialist-panel__copy">
                        @if($specialist->subtitle)
                            <p class="text-[11px] tracking-[0.2em] uppercase text-sand/80">{{ $specialist->subtitle }}</p>
                        @endif
                        <h3 class="font-display text-3xl md:text-4xl text-white mt-2">{{ $specialist->name }}</h3>
                        @if($specialist->teaser)
                            <p class="mt-3 max-w-md text-sm text-white/85 leading-relaxed">{{ $specialist->teaser }}</p>
                        @endif
                        <span class="mt-5 inline-flex text-[13px] tracking-[0.14em] uppercase text-sand">Explore this journey</span>
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

{{-- 7. Signature Journeys --}}
<section class="surface surface--beige relative py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-6 mb-12">
            <div class="max-w-2xl">
                <p class="section-eyebrow">{{ $featuredEyebrow }}</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">{{ $featuredHeading }}</h2>
                @if($featuredIntro)
                    <p class="mt-4 text-muted leading-relaxed">{{ $featuredIntro }}</p>
                @endif
            </div>
            <a href="{{ route('journeys.index') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">All journeys</a>
        </div>

        <div class="signature-grid reveal-stagger">
            @foreach($journeys as $journey)
                <article class="signature-card group">
                    <a href="{{ route('journeys.show', $journey) }}" class="signature-card__media">
                        @if($journey->coverUrl())
                            <img src="{{ $journey->coverThumbUrl() ?: $journey->coverUrl() }}" alt="{{ $journey->name }}" loading="lazy" decoding="async">
                        @endif
                        @if($journey->duration_label || $journey->days)
                            <span class="signature-card__badge">{{ $journey->duration_label ?: ($journey->days.' days') }}</span>
                        @endif
                    </a>
                    <div class="signature-card__body">
                        <h3 class="font-display text-2xl text-charcoal">
                            <a href="{{ route('journeys.show', $journey) }}" class="hover:text-forest transition">{{ $journey->name }}</a>
                        </h3>
                        @if($journey->teaser)
                            <p class="mt-2 text-sm text-muted leading-relaxed line-clamp-2">{{ $journey->teaser }}</p>
                        @endif
                        <div class="mt-5 flex flex-col gap-2">
                            <a href="{{ route('plan', ['journey' => $journey->slug]) }}" class="btn-primary text-center text-xs">Request private proposal</a>
                            <a href="{{ route('journeys.show', $journey) }}" class="text-center text-sm tracking-[0.12em] uppercase text-forest hover:opacity-70 transition">View itinerary</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- 8. Why Pearl Pulse --}}
<section class="surface surface--white relative py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Why Pearl Pulse</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">What sets a private journey apart</h2>
        </div>
        <div class="feature-strip reveal-stagger">
            @foreach($pillars as $index => $pillar)
                <div class="feature-strip__item">
                    <span class="feature-strip__mark">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="font-display text-2xl text-charcoal">{{ $pillar['title'] }}</h3>
                    @if(!empty($pillar['text']))
                        <p class="mt-3 text-sm text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. Our People --}}
@if($team->isNotEmpty())
<section class="surface surface--beige relative py-20 lg:py-28 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal text-center mb-14">
            <p class="section-eyebrow">Our people</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Meet the people who will look after you</h2>
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

{{-- 10. Conservation --}}
<section class="reason-stage">
    @if($reasonImage)
        <div class="reason-stage__media" aria-hidden="true">
            <img src="{{ $reasonImage }}" alt="" loading="lazy" decoding="async">
        </div>
    @endif
    <div class="reason-stage__veil" aria-hidden="true"></div>
    <div class="reason-stage__copy reveal">
        <p class="text-[11px] tracking-[0.22em] uppercase text-sand/55 mb-3">Conservation</p>
        <h2 class="font-display text-4xl md:text-5xl text-white leading-tight">Travel with a reason</h2>
        <p class="mt-5 text-sand/80 leading-relaxed max-w-lg">Permits fund protection. Local guides and camps create employment. Low-impact routing keeps wild places wild. Every journey can leave more than footprints.</p>
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

{{-- 12. Selected stays --}}
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

{{-- 13. Guest Voices --}}
@if($reviews->isNotEmpty())
<section class="surface surface--brown relative py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-12">
            <div>
                <p class="section-eyebrow">Guest voices</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">What travellers say</h2>
            </div>
            <a href="{{ route('reviews.index') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">Read all guest reviews</a>
        </div>
        <div class="guest-voices reveal-stagger">
            @foreach($reviews as $review)
                <blockquote class="guest-voices__card">
                    <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                    <footer class="mt-6 pt-4 border-t border-forest/15 text-sm text-muted">
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

{{-- 14. From the Field --}}
@if($fieldNotes->isNotEmpty())
<section class="surface surface--white py-20 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mb-10 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-eyebrow">From the Field</p>
                <h2 class="font-display text-4xl md:text-5xl text-charcoal">Notes from East Africa</h2>
            </div>
            <a href="{{ route('insiders.index', ['type' => 'field']) }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">All field notes</a>
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

{{-- 15. Quick FAQs --}}
@if($faqs->isNotEmpty())
<section class="surface surface--beige relative py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <div class="reveal text-center mb-10">
            <p class="section-eyebrow">Quick FAQs</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Questions guests ask first</h2>
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
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-sm tracking-[0.14em] uppercase text-white/75 hover:text-white transition [text-shadow:0_1px_12px_rgb(0_0_0_/_0.4)]">WhatsApp</a>
    @endif
</x-page-cta>
@endsection
