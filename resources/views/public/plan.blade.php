@extends('layouts.public')

@section('title', 'Plan your journey | Pearl Pulse Safaris')
@section('meta_description', 'Tell us what you are dreaming about. We will design a private proposal around you.')

@section('content')
<section class="surface surface--white pt-16 pb-10 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Plan your journey</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal leading-tight">Tell us what you are dreaming about</h1>
        <p class="mt-5 text-muted leading-relaxed text-lg">Start with destinations and timing. We will design a private proposal around you.</p>
        @if(!empty($whatsappUrl ?? null))
            <p class="mt-4 text-sm text-muted">
                Prefer WhatsApp?
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="text-forest hover:opacity-70 transition">Message us from Uganda</a>
            </p>
        @endif
    </div>
</section>

<section class="surface surface--beige pb-24 lg:pb-32">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 pt-4 lg:pt-8">
        <livewire:enquiry-form :journey-slug="request('journey')" />
    </div>
</section>
@endsection
