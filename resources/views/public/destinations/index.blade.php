@extends('layouts.public')

@section('title', 'Destinations | Pearl Pulse Safaris')
@section('meta_description', 'Explore Uganda, Rwanda, Kenya, and Tanzania — parks and places we know from the ground.')

@section('content')
<section class="bg-cream pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Destinations</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">Where do you want to go?</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Four countries. Open a country for why we travel there, then a park for seasons, how long to stay, and how a day actually unfolds. These pages are the map — journeys are how we put the nights together.</p>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($countries as $country)
            <x-country-card :country="$country" />
        @endforeach
    </div>
</section>
<x-page-cta heading="Not sure where to begin?" text="The Journey Finder can help — or write to us." button="Find your journey" :href="route('journeys.finder')" />
@endsection
