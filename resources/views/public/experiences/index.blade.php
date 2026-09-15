@extends('layouts.public')

@section('title', 'Experiences | Pearl Pulse Safaris')
@section('meta_description', 'Gorilla trekking, Big Five, chimpanzees, photography, culture, wellness, and more — how you want to experience Africa.')

@section('content')
<section class="bg-white pt-16 pb-12 lg:pt-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Experiences</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">How do you want to experience Africa?</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Gorilla mornings, open plains, water, culture, or rest. Each page explains where it belongs, how we pace it, and which journeys already include it.</p>
    </div>
</section>
<section class="bg-cream pb-24 pt-10">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 reveal-stagger">
        @foreach($experiences as $experience)
            <div class="lift-card">
                <x-experience-card :experience="$experience" />
            </div>
        @endforeach
    </div>
</section>
<x-page-cta heading="Ready to meet the wild?" text="Tell us which experiences matter — we will build around them." />
@endsection
