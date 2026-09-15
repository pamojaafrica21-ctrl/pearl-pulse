@extends('layouts.public')

@section('title', $country->name.' journeys | Pearl Pulse Safaris')
@section('meta_description', $country->teaser ?: 'Private '.$country->name.' safari journeys.')

@section('content')
<section class="bg-white pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">{{ $country->name }}</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">{{ $country->name }} journeys</h1>
        @if($country->teaser)
            <p class="mt-4 max-w-2xl text-muted">{{ $country->teaser }}</p>
        @endif
    </div>
</section>
<section class="bg-white pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($journeys as $journey)
            <x-journey-card :journey="$journey" />
        @empty
            <p class="text-muted">Journeys for {{ $country->name }} are being prepared. <a href="{{ route('plan') }}" class="text-charcoal underline">Plan a journey</a> and we will design one.</p>
        @endforelse
    </div>
</section>
<x-page-cta heading="Your {{ $country->name }} journey starts here" button="Plan a {{ $country->name }} journey" />
@endsection
