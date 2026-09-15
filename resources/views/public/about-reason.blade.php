@extends('layouts.public')

@section('title', 'Travel with a reason | Pearl Pulse Safaris')
@section('meta_description', 'Our journeys connect conservation, communities, and local people.')

@section('content')
<section class="bg-white pt-16 pb-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">Travel with a reason</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Conservation, community, local people</h1>
        <div class="prose-safari mt-10">
            <p>Travelling with Pearl Pulse can contribute to the places and people that make these journeys possible. We work with camps, conservation projects, and community partners so that a booking is not extractive.</p>
            <p>This is not a slogan on a footer. It is how we choose stays, guides, and the days we recommend — and when we say no to something that looks impressive but costs the land too much.</p>
        </div>
    </div>
</section>
<x-page-cta heading="Discover how we would design your journey" />
@endsection
