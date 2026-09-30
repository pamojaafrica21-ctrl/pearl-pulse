@extends('layouts.public')

@section('title', $heading.' | Pearl Pulse Safaris')
@section('meta_description', 'Private, tailor-made safari journeys across Uganda, Rwanda, Kenya, and Tanzania.')

@section('content')
@php
    $signatureJourneys = $type === ''
        ? $journeys->where('is_signature', true)->take(6)
        : collect();
@endphp

<section class="journeys-index-hero surface surface--white pt-16 pb-10 lg:pt-24 lg:pb-14">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Journeys</p>
        <h1 class="font-display text-5xl md:text-6xl lg:text-7xl text-charcoal leading-[0.95]">{{ $heading }}</h1>
        <p class="mt-5 max-w-2xl text-muted leading-relaxed text-lg">
            Private itineraries you can reshape. Open a journey for the full day-by-day — then we tailor nights, permits, and pace into a private proposal.
        </p>

        <div class="journeys-filters mt-10">
            <a href="{{ route('journeys.index') }}" class="journeys-filters__link {{ $type === '' ? 'is-active' : '' }}">All</a>
            <a href="{{ route('journeys.index', ['type' => 'signature']) }}" class="journeys-filters__link {{ $type === 'signature' ? 'is-active' : '' }}">Signature</a>
            <a href="{{ route('journeys.index', ['type' => 'multi']) }}" class="journeys-filters__link {{ $type === 'multi' ? 'is-active' : '' }}">Multi-country</a>
            @foreach($countries as $country)
                <a href="{{ route('journeys.country', $country) }}" class="journeys-filters__link">{{ $country->name }}</a>
            @endforeach
            <a href="{{ route('journeys.finder') }}" class="journeys-filters__link journeys-filters__link--accent">Journey Finder</a>
        </div>
    </div>
</section>

@if($signatureJourneys->isNotEmpty())
<section class="signature-rail surface surface--beige relative pt-12 pb-8 lg:pt-16 overflow-hidden">
    <div
        class="signature-rail__layout mx-auto max-w-7xl"
        data-card-scroller
    >
        <div class="signature-rail__intro reveal px-5 lg:px-8">
            <p class="section-eyebrow">Signature</p>
            <h2 class="font-display text-3xl md:text-4xl text-charcoal">Begin with a signature outline</h2>
            <p class="mt-3 text-muted max-w-md leading-relaxed">Cinematic starting points — not fixed products. Each one is reshaped around you.</p>
        </div>
        <div
            class="signature-rail__scroller"
            data-card-scroller-viewport
            tabindex="0"
            aria-label="Signature journeys"
        >
            <div class="signature-rail__track" data-card-scroller-track>
                @foreach($signatureJourneys as $journey)
                    <x-journey-card :journey="$journey" variant="signature" />
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="journeys-index-grid surface surface--{{ $signatureJourneys->isNotEmpty() ? 'white' : 'beige' }} pb-24 pt-12 lg:pt-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        @if($signatureJourneys->isNotEmpty())
            <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
                <h2 class="font-display text-3xl text-charcoal">All sample journeys</h2>
                <p class="text-sm text-muted">{{ $journeys->count() }} itinerar{{ $journeys->count() === 1 ? 'y' : 'ies' }}</p>
            </div>
        @endif

        <div class="journeys-grid reveal-stagger">
            @forelse($journeys as $journey)
                <x-journey-card :journey="$journey" />
            @empty
                <p class="text-muted col-span-full">Journeys are being prepared. <a href="{{ route('plan') }}" class="text-forest underline">Plan a journey</a> with us.</p>
            @endforelse
        </div>
    </div>
</section>

<x-page-cta heading="Tell us what you are dreaming about" text="We will design the journey around you." />
@endsection
