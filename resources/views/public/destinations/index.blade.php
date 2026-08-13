@extends('layouts.public')

@section('title', $heading.' — '.config('app.name'))
@section('meta_description', $intro ?: 'Explore safari destinations across Uganda, Kenya, Tanzania, and Rwanda with Pearl Pulse Safaris.')

@section('content')
<div class="pt-28 pb-6 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $eyebrow }}</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">{{ $heading }}</h1>
        @if($intro)
            <p class="mt-4 max-w-xl text-muted">{{ $intro }}</p>
        @endif
    </div>
</div>

<div class="pb-20 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <livewire:destinations-index />
    </div>
</div>
@endsection
