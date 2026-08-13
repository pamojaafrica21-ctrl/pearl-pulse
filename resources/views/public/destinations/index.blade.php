@extends('layouts.public')

@section('title', 'Destinations — '.config('app.name'))
@section('meta_description', 'Explore safari destinations across Uganda, Kenya, Tanzania, and Rwanda with Pearl Pulse Safaris.')

@section('content')
<div class="pt-28 pb-6 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">East Africa</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">Destinations</h1>
        <p class="mt-4 max-w-xl text-muted">From misty gorilla forests to endless savannah — choose your next chapter.</p>
    </div>
</div>

<div class="pb-20 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <livewire:destinations-index />
    </div>
</div>
@endsection
