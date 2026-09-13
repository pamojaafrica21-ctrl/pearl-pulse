@extends('layouts.public')

@section('title', $heading.' | Pearl Pulse Safaris')
@section('meta_description', 'Private, tailor-made safari journeys across Uganda, Rwanda, Kenya, and Tanzania.')

@section('content')
<section class="bg-cream pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Journeys</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">{{ $heading }}</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Private itineraries you can reshape. Open a journey for the full day-by-day, who it is for, and what is included — then we tailor nights, permits, and pace. A from-price where it helps, or a private proposal when the route is too specific for a number on a card.</p>
        <div class="mt-8 flex flex-wrap gap-3 text-sm">
            <a href="{{ route('journeys.index') }}" class="{{ $type === '' ? 'text-forest' : 'text-muted hover:text-forest' }}">All</a>
            <a href="{{ route('journeys.index', ['type' => 'signature']) }}" class="{{ $type === 'signature' ? 'text-forest' : 'text-muted hover:text-forest' }}">Signature</a>
            <a href="{{ route('journeys.index', ['type' => 'multi']) }}" class="{{ $type === 'multi' ? 'text-forest' : 'text-muted hover:text-forest' }}">Multi-country</a>
            @foreach($countries as $country)
                <a href="{{ route('journeys.country', $country) }}" class="text-muted hover:text-forest">{{ $country->name }}</a>
            @endforeach
            <a href="{{ route('journeys.finder') }}" class="text-gold hover:text-forest">Journey Finder</a>
        </div>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($journeys as $journey)
            <x-journey-card :journey="$journey" />
        @endforeach
    </div>
</section>
<x-page-cta heading="Tell us what you are dreaming about" text="We will design the journey around you." />
@endsection
