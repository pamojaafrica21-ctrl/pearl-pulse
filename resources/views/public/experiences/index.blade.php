@extends('layouts.public')

@section('title', 'Experiences | Pearl Pulse Safaris')
@section('meta_description', 'Gorilla trekking, Big Five, chimpanzees, photography, culture, wellness, and more — how you want to experience Africa.')

@section('content')
<section class="bg-cream pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Experiences</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">How do you want to experience Africa?</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Gorilla mornings, open plains, water, culture, or rest. Each page explains where it belongs, how we pace it, and which journeys already include it — so you can choose a feeling, not only a park name.</p>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($experiences as $experience)
            <x-experience-card :experience="$experience" />
        @endforeach
    </div>
</section>
<x-page-cta heading="Ready to meet the wild?" text="Tell us which experiences matter — we will build around them." />
@endsection
