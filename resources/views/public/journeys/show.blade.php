@extends('layouts.public')

@section('title', $journey->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($journey->seoDescription(), 160))

@section('content')
@php
    $cover = $journey->coverUrl();
    $video = $journey->videoPlaybackUrl();
    $itinerary = $journey->itineraryDays();
    $dayCount = count($itinerary);
    $countriesLabel = $journey->countries->pluck('name')->join(', ');
    $hasStays = $journey->stays->isNotEmpty();
    $hasMap = (bool) $journey->map_embed_url;
    $hasDestinations = $journey->destinations->isNotEmpty();
@endphp

<section class="relative min-h-[78svh] flex items-end overflow-hidden bg-forest">
    <x-hero-media :video="$video" :image="$cover" :alt="$journey->name" />
    <div class="absolute inset-0 bg-gradient-to-t from-forest/92 via-forest/40 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Journeys', 'href' => route('journeys.index')],
            ['label' => $journey->name],
        ]" />
        <p class="mt-5 text-[13px] tracking-[0.2em] uppercase text-sand/70 fade-up">Sample itinerary</p>
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-sand mt-3 fade-up leading-[0.95]">{{ $journey->name }}</h1>
        @if($journey->subtitle)
            <p class="mt-4 text-lg md:text-xl text-sand/80 font-light max-w-2xl fade-up" style="animation-delay: 0.08s">{{ $journey->subtitle }}</p>
        @endif
        <div class="journey-hero-meta mt-8 fade-up" style="animation-delay: 0.14s">
            @if($journey->duration_label)
                <span>{{ $journey->duration_label }}</span>
            @endif
            @if($countriesLabel)
                <span>{{ $countriesLabel }}</span>
            @endif
            <span>{{ $journey->priceLabel() }}</span>
        </div>
    </div>
</section>

@php
    $stayCount = $journey->stays->count();
    $jumpItems = array_values(array_filter([
        [
            'id' => 'overview',
            'label' => 'Overview',
            'detail' => $journey->duration_label
                ? $journey->duration_label.($countriesLabel ? ' · '.$countriesLabel : '')
                : ($countriesLabel ?: 'The journey at a glance'),
        ],
        $dayCount ? [
            'id' => 'days',
            'label' => 'Day by day',
            'detail' => $dayCount.' day'.($dayCount === 1 ? '' : 's').' outlined',
        ] : null,
        $hasStays ? [
            'id' => 'stays',
            'label' => 'Stays',
            'detail' => $stayCount.' selected lodge'.($stayCount === 1 ? '' : 's'),
        ] : null,
        $hasMap ? [
            'id' => 'map',
            'label' => 'Map',
            'detail' => 'Route overview',
        ] : null,
        [
            'id' => 'included',
            'label' => 'Included',
            'detail' => 'What is covered',
        ],
    ]));
    $jumpSections = array_column($jumpItems, 'id');
@endphp

<script>
    window.journeyJumpNav = function journeyJumpNav() {
        return {
            active: 'overview',
            sections: @json($jumpSections),
            observer: null,
            init() {
                const ids = this.sections;
                const nodes = ids
                    .map((id) => document.getElementById(id))
                    .filter(Boolean);

                if (!nodes.length) {
                    return;
                }

                const measureOffset = () => {
                    const chrome = parseFloat(
                        getComputedStyle(document.documentElement)
                            .getPropertyValue('--site-chrome-height')
                    ) || 76;
                    const navHeight = this.$el.offsetHeight || 48;
                    return chrome + navHeight + 8;
                };

                const syncFromScroll = () => {
                    const offset = measureOffset();
                    let current = ids[0];
                    for (const id of ids) {
                        const el = document.getElementById(id);
                        if (!el) continue;
                        if (el.getBoundingClientRect().top - offset <= 1) {
                            current = id;
                        }
                    }
                    if (this.active !== current) {
                        this.active = current;
                    }
                };

                this.observer = new IntersectionObserver(
                    () => syncFromScroll(),
                    {
                        rootMargin: '-20% 0px -55% 0px',
                        threshold: [0, 0.1, 0.25, 0.5, 0.75],
                    }
                );

                nodes.forEach((node) => this.observer.observe(node));
                window.addEventListener('scroll', syncFromScroll, { passive: true });
                window.addEventListener('resize', syncFromScroll, { passive: true });
                syncFromScroll();

                this._onScroll = syncFromScroll;
            },
            scrollTo(event, id) {
                event.preventDefault();
                const el = document.getElementById(id);
                if (!el) return;
                this.active = id;
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                history.replaceState(null, '', '#' + id);
            },
            destroy() {
                this.observer?.disconnect();
                if (this._onScroll) {
                    window.removeEventListener('scroll', this._onScroll);
                    window.removeEventListener('resize', this._onScroll);
                }
            },
        };
    };
</script>

<nav
    class="journey-jump"
    aria-label="On this page"
    x-data="journeyJumpNav()"
>
    <div class="journey-jump__inner">
        <div class="journey-jump__links" role="list">
            @foreach($jumpItems as $item)
                <a
                    href="#{{ $item['id'] }}"
                    class="journey-jump__link"
                    role="listitem"
                    :class="{ 'is-active': active === '{{ $item['id'] }}' }"
                    @click="scrollTo($event, '{{ $item['id'] }}')"
                    :aria-current="active === '{{ $item['id'] }}' ? 'true' : 'false'"
                >
                    <span class="journey-jump__label">{{ $item['label'] }}</span>
                    <span class="journey-jump__detail">{{ $item['detail'] }}</span>
                </a>
            @endforeach
        </div>
        <div class="journey-jump__aside" aria-hidden="true">
            <p class="journey-jump__aside-name">{{ $journey->name }}</p>
            <p class="journey-jump__aside-meta">{{ $journey->priceLabel() }}</p>
        </div>
    </div>
</nav>

<section id="overview" class="surface surface--white py-16 lg:py-24 scroll-mt-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-7 reveal">
            <p class="text-sm tracking-[0.04em] text-muted leading-relaxed max-w-xl">
                A starting outline — we reshape nights, permits, and pace into a private proposal.
            </p>

            @if($journey->teaser)
                <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug mt-8">{{ $journey->teaser }}</p>
            @endif

            @if(!empty($journey->highlights))
                <ul class="mt-10 space-y-4">
                    @foreach($journey->highlights as $item)
                        <li class="flex gap-4 border-t border-charcoal/10 pt-4">
                            <span class="text-[13px] tracking-[0.16em] uppercase text-muted shrink-0 w-28">{{ $item['label'] ?? 'Highlight' }}</span>
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
                    <h2 class="text-[13px] tracking-[0.2em] uppercase text-muted">At a glance</h2>
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
                    @if($countriesLabel)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Countries</dt>
                            <dd class="mt-1 text-charcoal">{{ $countriesLabel }}</dd>
                        </div>
                    @endif
                    @if($dayCount)
                        <div>
                            <dt class="text-xs tracking-[0.15em] uppercase text-muted">Outline</dt>
                            <dd class="mt-1 text-charcoal">{{ $dayCount }} day{{ $dayCount === 1 ? '' : 's' }} detailed below</dd>
                        </div>
                    @endif
                </dl>
                <a href="{{ route('plan', ['journey' => $journey->slug]) }}" class="btn-primary mt-8 w-full text-center">Customize this journey</a>
                <p class="mt-4 text-xs text-muted leading-relaxed">100% privately guided. Sample nights and stays are starting points — never fixed products.</p>
            </div>
        </aside>
    </div>
</section>

@if($dayCount)
<section
    id="days"
    class="surface surface--beige py-16 lg:py-24 scroll-mt-28"
    x-data="{ open: 0 }"
>
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal max-w-2xl mb-10">
            <h2 class="font-display text-4xl md:text-5xl text-charcoal">Day by day</h2>
            <p class="mt-3 text-muted leading-relaxed">Open any day for the rhythm, lodging, and meals. We adjust transfers and rest nights when we write your proposal.</p>
        </div>

        <ol class="day-track day-track--rich reveal">
            @foreach($itinerary as $i => $day)
                @php
                    $stay = !empty($day['stay_id']) ? ($itineraryStays[(int) $day['stay_id']] ?? null) : null;
                    $stayLabel = $journey->dayStayLabel($day, $stay);
                    $meals = $journey->dayMeals($day);
                    $dayImage = $journey->dayImageUrl($day['image_path'] ?? null)
                        ?: ($stay?->coverUrl());
                @endphp
                <li
                    class="day-track__item"
                    :class="{ 'is-open': open === {{ $i }} }"
                >
                    <button
                        type="button"
                        class="day-track__toggle"
                        @click="open = open === {{ $i }} ? -1 : {{ $i }}"
                        :aria-expanded="(open === {{ $i }}).toString()"
                    >
                        <span class="day-track__mark">{{ $day['day'] ?? $loop->iteration }}</span>
                        <span class="day-track__summary">
                            <span class="text-xs tracking-[0.18em] uppercase text-muted">Day {{ $day['day'] ?? $loop->iteration }}</span>
                            <span class="font-display text-2xl md:text-3xl text-charcoal mt-1 block">{{ $day['title'] ?? '' }}</span>
                            @if($stayLabel)
                                <span class="day-track__stay-preview mt-1 block">{{ $stayLabel }}</span>
                            @endif
                        </span>
                        <span class="day-track__chevron" aria-hidden="true"></span>
                    </button>

                    <div
                        class="day-track__panel"
                        x-show="open === {{ $i }}"
                        x-cloak
                    >
                        <div class="day-track__body">
                            <div class="day-track__copy">
                                @if(!empty($day['description']))
                                    <p class="text-muted leading-relaxed">{{ $day['description'] }}</p>
                                @endif

                                @if($meals !== [])
                                    <div class="day-meals mt-5">
                                        <p class="text-xs tracking-[0.16em] uppercase text-muted mb-2">Meals</p>
                                        <div class="day-meals__chips">
                                            @foreach($meals as $code)
                                                <span class="day-meals__chip">{{ ['B' => 'Breakfast', 'L' => 'Lunch', 'D' => 'Dinner'][$code] ?? $code }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($stayLabel)
                                    <div class="day-lodging mt-6">
                                        <p class="text-xs tracking-[0.16em] uppercase text-muted mb-2">Overnight</p>
                                        @if($stay)
                                            <a href="{{ route('stays.show', $stay) }}" class="day-lodging__link">
                                                <span class="font-display text-xl text-charcoal">{{ $stay->name }}</span>
                                                @if($stay->location)
                                                    <span class="block text-sm text-muted mt-1">{{ $stay->location }}</span>
                                                @endif
                                            </a>
                                        @else
                                            <p class="font-display text-xl text-charcoal">{{ $stayLabel }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            @if($dayImage)
                                <figure class="day-track__media">
                                    <img src="{{ $dayImage }}" alt="{{ $day['title'] ?? $journey->name }}" loading="lazy" decoding="async">
                                </figure>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endif

@if($hasDestinations)
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
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

@if($hasStays)
<section id="stays" class="surface surface--beige py-16 lg:py-20 overflow-hidden scroll-mt-28">
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

@if($hasMap)
<section id="map" class="surface surface--white pb-4 scroll-mt-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 mb-6">
        <h2 class="font-display text-4xl text-charcoal reveal">Map</h2>
    </div>
    <div class="aspect-[16/9] lg:aspect-[21/9] bg-forest/5">
        <iframe src="{{ $journey->map_embed_url }}" class="h-full w-full border-0" loading="lazy" title="Journey map"></iframe>
    </div>
</section>
@endif

<section id="included" class="surface surface--beige py-16 lg:py-20 scroll-mt-28">
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

<div class="journey-sticky-bar" aria-label="Journey actions">
    <div class="journey-sticky-bar__inner">
        <div class="journey-sticky-bar__meta">
            <p class="font-display text-lg text-charcoal leading-tight truncate">{{ $journey->name }}</p>
            <p class="text-xs tracking-[0.08em] uppercase text-muted mt-0.5">
                @if($journey->duration_label){{ $journey->duration_label }} · @endif{{ $journey->priceLabel() }}
            </p>
        </div>
        <div class="journey-sticky-bar__actions">
            <livewire:favorite-button :journey="$journey" :key="'fav-sticky-'.$journey->id" />
            <a href="{{ route('plan', ['journey' => $journey->slug]) }}" class="btn-primary text-xs whitespace-nowrap">Customize</a>
        </div>
    </div>
</div>

@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'TouristTrip',
    'name' => $journey->name,
    'description' => $journey->seoDescription(),
    'touristType' => 'Safari',
    'itinerary' => array_map(fn ($d) => ['@type' => 'TouristDestination', 'name' => $d['title'] ?? ''], $itinerary),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endsection
