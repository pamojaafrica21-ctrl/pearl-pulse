@extends('layouts.public')

@section('title', 'Selected stays | Pearl Pulse Safaris')
@section('meta_description', 'Places we have selected for location, character, and how they complement your journey. We do not own lodges.')

@section('content')
<section class="bg-white pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">Selected stays</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Places we have selected</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">We do not own lodges. We choose stays for location, character, and how they sit in the day — forest-edge after a trek, a conservancy camp for fewer vehicles, a highland night before the briefing. Open a stay to read why we select it.</p>
    </div>
</section>
<section class="bg-white pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($stays as $stay)
            <x-stay-card :stay="$stay" />
        @endforeach
    </div>
</section>
<x-page-cta heading="Tell us how you like to stay" text="Essential, premium, or signature — we will match the journey." />
@endsection
