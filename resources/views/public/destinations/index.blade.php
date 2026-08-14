@extends('layouts.public')

@section('title', $heading.' — '.config('app.name'))
@section('meta_description', $intro ?: 'Explore safari destinations across Uganda, Kenya, Tanzania, and Rwanda with Pearl Pulse Safaris.')

@section('content')
<div class="pt-28 pb-6 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $eyebrow }}</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">{{ $heading }}</h1>
        @if($intro)
            <p class="mt-4 max-w-2xl text-muted text-lg leading-relaxed">{{ $intro }}</p>
        @endif
    </div>
</div>

<div class="pb-16 bg-cream">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <livewire:destinations-index />
    </div>
</div>

@if($noteHeading || $noteBody)
<section class="bg-sand/40 py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        @if($noteHeading)
            <h2 class="font-display text-3xl md:text-4xl text-forest">{{ $noteHeading }}</h2>
        @endif
        @if($noteBody)
            <p class="mt-5 text-muted leading-relaxed whitespace-pre-line">{{ $noteBody }}</p>
        @endif
    </div>
</section>
@endif

@if($ctaHeading || $ctaText)
<section class="bg-forest text-sand py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center">
        @if($ctaHeading)
            <h2 class="font-display text-3xl md:text-4xl">{{ $ctaHeading }}</h2>
        @endif
        @if($ctaText)
            <p class="mt-4 text-sand/75 leading-relaxed">{{ $ctaText }}</p>
        @endif
        <div class="mt-8">
            <a href="{{ route('contact') }}" class="btn-outline">Enquire now</a>
        </div>
    </div>
</section>
@endif
@endsection
