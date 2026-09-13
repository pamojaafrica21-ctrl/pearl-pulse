@extends('layouts.public')

@section('title', 'Contact — '.config('app.name'))
@section('meta_description', 'Get in touch with Pearl Pulse Safaris to plan your East African journey.')

@section('content')
<div class="pt-16 pb-20 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-14 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Contact</p>
            <h1 class="font-display text-5xl md:text-6xl text-forest">Let’s plan your journey</h1>
            <div class="mt-10 space-y-4 text-muted">
                @if($contact['address'])
                    <p>{{ $contact['address'] }}</p>
                @endif
                @if($contact['phone'])
                    <p><a class="text-forest hover:underline" href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a></p>
                @endif
                @if($contact['email'])
                    <p><a class="text-forest hover:underline" href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a></p>
                @endif
            </div>
        </div>
        <div class="lg:col-span-7 bg-white/60 border border-sand-deep/30 p-6 md:p-10">
            <livewire:enquiry-form />
        </div>
    </div>
</div>
@endsection
