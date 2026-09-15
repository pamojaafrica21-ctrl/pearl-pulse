@extends('layouts.public')

@section('title', $heading.' | Pearl Pulse Safaris')
@section('meta_description', 'Private, tailor-made safari journeys across Uganda, Rwanda, Kenya, and Tanzania.')

@section('content')
<section class="bg-white pt-16 pb-12 lg:pt-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Journeys</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">{{ $heading }}</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Private itineraries you can reshape. Open a journey for the full day-by-day — then we tailor nights, permits, and pace into a private proposal.</p>
        <div class="mt-8 flex flex-wrap gap-x-5 gap-y-2 text-sm tracking-[0.04em]">
            <a href="{{ route('journeys.index') }}" class="{{ $type === '' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }} transition">All</a>
            <a href="{{ route('journeys.index', ['type' => 'signature']) }}" class="{{ $type === 'signature' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }} transition">Signature</a>
            <a href="{{ route('journeys.index', ['type' => 'multi']) }}" class="{{ $type === 'multi' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }} transition">Multi-country</a>
            @foreach($countries as $country)
                <a href="{{ route('journeys.country', $country) }}" class="text-muted hover:text-charcoal transition">{{ $country->name }}</a>
            @endforeach
            <a href="{{ route('journeys.finder') }}" class="text-muted hover:text-charcoal transition">Journey Finder</a>
        </div>
    </div>
</section>
<section class="bg-cream pb-24 pt-10">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3 reveal-stagger">
        @forelse($journeys as $journey)
            <div class="lift-card">
                <x-journey-card :journey="$journey" />
            </div>
        @empty
            <p class="text-muted col-span-full">Journeys are being prepared. <a href="{{ route('plan') }}" class="text-forest underline">Plan a journey</a> with us.</p>
        @endforelse
    </div>
</section>
<x-page-cta heading="Tell us what you are dreaming about" text="We will design the journey around you." />
@endsection
