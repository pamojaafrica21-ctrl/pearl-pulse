@extends('layouts.public')

@section('title', 'Plan your journey | Pearl Pulse Safaris')
@section('meta_description', 'Tell us where you want to go, how you want to travel, and we will design a private East African journey around you.')

@section('content')
<section class="bg-white pt-16 pb-10">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <p class="section-eyebrow">Plan your journey</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Tell us what you are dreaming about</h1>
        <p class="mt-4 text-muted leading-relaxed">Start with destinations and timing. We will design a private proposal around you. Prefer WhatsApp? Use the button on the site — we reply from Uganda.</p>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 border border-charcoal/8 bg-white p-6 sm:p-10">
        <livewire:enquiry-form :journey-slug="request('journey')" />
    </div>
</section>
@endsection
