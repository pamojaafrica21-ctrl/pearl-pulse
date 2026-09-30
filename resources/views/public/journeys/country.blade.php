@extends('layouts.public')

@section('title', $country->name.' journeys | Pearl Pulse Safaris')
@section('meta_description', $country->teaser ?: 'Private '.$country->name.' safari journeys.')

@section('content')
@php
    $cover = $country->coverUrl();
    $count = $journeys->count();
@endphp

<section class="relative min-h-[52svh] flex items-end overflow-hidden bg-forest">
    @if($cover)
        <img src="{{ $cover }}" alt="{{ $country->name }}" class="absolute inset-0 h-full w-full object-cover" loading="eager" decoding="async">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/40 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-14 pt-20 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Journeys', 'href' => route('journeys.index')],
            ['label' => $country->name],
        ]" />
        <p class="mt-5 text-[13px] tracking-[0.2em] uppercase text-sand/70">{{ $country->name }}</p>
        <h1 class="font-display text-5xl md:text-6xl lg:text-7xl text-sand mt-3 leading-[0.95]">{{ $country->name }} journeys</h1>
        @if($country->teaser)
            <p class="mt-4 max-w-2xl text-sand/80 leading-relaxed text-lg">{{ $country->teaser }}</p>
        @endif
        <p class="mt-6 text-sm tracking-[0.08em] uppercase text-sand/65">
            {{ $count }} sample journey{{ $count === 1 ? '' : 's' }} · fully customisable
        </p>
    </div>
</section>

<section class="surface surface--white pt-10 pb-6">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="journeys-filters">
            <a href="{{ route('journeys.index') }}" class="journeys-filters__link">All journeys</a>
            <a href="{{ route('journeys.index', ['type' => 'signature']) }}" class="journeys-filters__link">Signature</a>
            <a href="{{ route('journeys.index', ['type' => 'multi']) }}" class="journeys-filters__link">Multi-country</a>
            <a href="{{ route('journeys.finder') }}" class="journeys-filters__link journeys-filters__link--accent">Journey Finder</a>
            <a href="{{ route('plan') }}" class="journeys-filters__link journeys-filters__link--accent">Plan {{ $country->name }}</a>
        </div>
    </div>
</section>

<section class="journeys-index-grid surface surface--beige pb-24 pt-10 lg:pt-14">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="journeys-grid reveal-stagger">
            @forelse($journeys as $journey)
                <x-journey-card :journey="$journey" />
            @empty
                <p class="text-muted col-span-full">
                    Journeys for {{ $country->name }} are being prepared.
                    <a href="{{ route('plan') }}" class="text-charcoal underline">Plan a journey</a>
                    and we will design one.
                </p>
            @endforelse
        </div>
    </div>
</section>

<x-page-cta
    heading="Your {{ $country->name }} journey starts here"
    text="Tell us your window and pace — we will reply with a private outline."
    button="Plan a {{ $country->name }} journey"
    :href="route('plan')"
/>
@endsection
