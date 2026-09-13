@extends('layouts.public')

@section('title', config('app.name').' — Private journeys into Africa’s wild heart')
@section('meta_description', $heroTagline)

@section('content')
<section class="relative min-h-[calc(100svh-4.75rem)] flex items-end overflow-hidden bg-forest">
    @if($heroVideoUrl)
        <video class="absolute inset-0 h-full w-full object-cover fade-in" autoplay muted loop playsinline poster="{{ $heroImageUrl }}">
            <source src="{{ $heroVideoUrl }}" type="video/mp4">
        </video>
    @elseif($heroImageUrl)
        <img src="{{ $heroImageUrl }}" alt="East African savannah at last light" class="absolute inset-0 h-full w-full object-cover fade-in">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/80 via-forest/20 to-black/10"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-20 pt-16 lg:px-8 lg:pb-28">
        @if($heroKicker)
            <p class="text-xs tracking-[0.22em] uppercase text-sand/70 fade-up">{{ $heroKicker }}</p>
        @endif
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl text-sand leading-[0.95] max-w-5xl fade-up mt-4">
            {{ $heroHeadline }}
        </h1>
        <p class="mt-6 max-w-xl text-sand/85 text-lg md:text-xl font-light leading-relaxed fade-up" style="animation-delay: 0.15s">
            {{ $heroTagline }}
        </p>
        <div class="mt-10 flex flex-wrap gap-4 fade-up" style="animation-delay: 0.3s">
            <a href="{{ route('plan') }}" class="btn-outline">Plan your journey</a>
            <a href="{{ route('journeys.index') }}" class="text-sm tracking-[0.14em] uppercase text-sand/80 hover:text-sand self-center">Explore journeys</a>
        </div>
    </div>
</section>

@if($homeIntroHeading || $homeIntroBody)
<section class="bg-cream py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="max-w-3xl">
            @if($homeIntroEyebrow)
                <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $homeIntroEyebrow }}</p>
            @endif
            @if($homeIntroHeading)
                <h2 class="font-display text-4xl md:text-5xl text-forest">{{ $homeIntroHeading }}</h2>
            @endif
            @if($homeIntroBody)
                <p class="mt-6 text-muted text-lg leading-relaxed whitespace-pre-line">{{ $homeIntroBody }}</p>
            @endif
        </div>
        <div class="mt-10">
            <a href="{{ route('about') }}" class="btn-outline-dark">Our story</a>
        </div>
    </div>
</section>
@endif

<section class="bg-cream pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Destinations</p>
        <h2 class="font-display text-4xl md:text-5xl text-forest">Four countries. One local team.</h2>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Uganda is home. Rwanda, Kenya, and Tanzania complete the map. Open a country to read why we travel there — parks, seasons, and how a journey typically unfolds.</p>
        <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($countries as $country)
                <x-country-card :country="$country" />
            @endforeach
        </div>
    </div>
</section>

<section class="bg-cream pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $featuredEyebrow }}</p>
        <h2 class="font-display text-4xl md:text-5xl text-forest">{{ $featuredHeading }}</h2>
        @if($featuredIntro)
            <p class="mt-4 text-muted leading-relaxed max-w-2xl">{{ $featuredIntro }}</p>
        @endif
        <div class="mt-12 grid gap-10 sm:grid-cols-3">
            @foreach($journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
        <div class="mt-14">
            <a href="{{ route('journeys.index') }}" class="btn-primary">All journeys</a>
        </div>
    </div>
</section>

<section class="bg-forest text-sand py-20 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Journey Finder</p>
        <h2 class="font-display text-4xl md:text-5xl">Not sure where to begin?</h2>
        <p class="mt-4 text-sand/75 leading-relaxed">Refine by country, duration, experience, and stay style. This is not a quiz — it is a quieter way to see what already exists, then we tailor from there.</p>
        <div class="mt-10">
            <a href="{{ route('journeys.finder') }}" class="btn-outline">Open the finder</a>
        </div>
    </div>
</section>

<section class="bg-cream py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Experiences</p>
        <h2 class="font-display text-4xl md:text-5xl text-forest">How do you want to move?</h2>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Gorilla mornings, open plains, water, culture, or rest. Each experience has its own page — where it happens, how we pace it, and which journeys include it.</p>
        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($experiences as $experience)
                <x-experience-card :experience="$experience" />
            @endforeach
        </div>
        <div class="mt-12">
            <a href="{{ route('experiences.index') }}" class="btn-outline-dark">All experiences</a>
        </div>
    </div>
</section>

@if(count($homePillars))
<section class="bg-cream pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Why Pearl Pulse</p>
        <h2 class="font-display text-4xl md:text-5xl text-forest">What sets the journey apart</h2>
        <div class="mt-14 grid gap-10 md:grid-cols-2 lg:grid-cols-3">
            @foreach($homePillars as $pillar)
                <div>
                    <h3 class="font-display text-2xl text-forest">{{ $pillar['title'] }}</h3>
                    <p class="mt-3 text-sm text-muted leading-relaxed">{{ $pillar['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="border-t border-sand-deep/20 bg-cream py-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-6">Continue</p>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('our-people') }}" class="group block">
                <h3 class="font-display text-2xl text-forest group-hover:text-forest-light">Our people</h3>
                <p class="mt-2 text-sm text-muted">Guides and planners who live this work.</p>
            </a>
            <a href="{{ route('true-pulse') }}" class="group block">
                <h3 class="font-display text-2xl text-forest group-hover:text-forest-light">True Pulse</h3>
                <p class="mt-2 text-sm text-muted">Guest photographs and stories we have approved.</p>
            </a>
            <a href="{{ route('stays.index') }}" class="group block">
                <h3 class="font-display text-2xl text-forest group-hover:text-forest-light">Selected stays</h3>
                <p class="mt-2 text-sm text-muted">Lodges we choose. We do not own them.</p>
            </a>
            <a href="{{ route('insiders.index') }}" class="group block">
                <h3 class="font-display text-2xl text-forest group-hover:text-forest-light">Insiders</h3>
                <p class="mt-2 text-sm text-muted">Guides from the ground — permits, seasons, packing.</p>
            </a>
        </div>
    </div>
</section>

<x-page-cta :heading="$homeCtaHeading" :text="$homeCtaText" :button="$homeCtaButton">
    @if($whatsappUrl)
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-sm tracking-[0.14em] uppercase text-sand/70 hover:text-sand">WhatsApp</a>
    @endif
</x-page-cta>
@endsection
