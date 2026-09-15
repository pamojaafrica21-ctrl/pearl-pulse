@extends('layouts.public')

@section('title', 'Destinations | Pearl Pulse Safaris')
@section('meta_description', 'Explore Uganda, Rwanda, Kenya, and Tanzania — parks and places we know from the ground.')

@section('content')
<section class="bg-white pt-16 pb-12 lg:pt-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Destinations</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Where do you want to go?</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Four countries. Open a country for why we travel there, then a park for seasons, how long to stay, and how a day actually unfolds.</p>
    </div>
</section>
<section class="bg-cream pb-24 pt-10">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-4 reveal-stagger">
        @foreach($countries as $country)
            <div class="lift-card">
                <x-country-card :country="$country" />
            </div>
        @endforeach
    </div>
</section>
<x-page-cta heading="Not sure where to begin?" text="Find your journey — or write to us for a private proposal." button="Find your journey" :href="route('journeys.finder')" />
@endsection
