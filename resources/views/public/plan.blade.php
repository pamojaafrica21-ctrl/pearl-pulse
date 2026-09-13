@extends('layouts.public')

@section('title', 'Plan your journey | Pearl Pulse Safaris')
@section('meta_description', 'Tell us where you want to go, how you want to travel, and we will design a private East African journey around you.')

@section('content')
<section class="bg-cream pt-16 pb-10">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Plan your journey</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">Tell us what you are dreaming about</h1>
        <p class="mt-4 text-muted">We will design the journey around you. Prefer WhatsApp? Use the button on the page — we reply from Uganda.</p>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <livewire:enquiry-form :journey-slug="request('journey')" />
    </div>
</section>
@endsection
