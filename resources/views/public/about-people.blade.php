@extends('layouts.public')

@section('title', 'Our people | Pearl Pulse Safaris')
@section('meta_description', 'The guides and team behind Pearl Pulse — local, visible, and part of your journey.')

@section('content')
<section class="bg-white pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">Our people</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Your journey. Our people.</h1>
        <p class="mt-4 max-w-2xl text-muted">We want the people behind Pearl Pulse to be visible — guides and specialists who know the ground.</p>
    </div>
</section>
<section class="bg-white pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($team as $member)
            <div>
                @if($member->coverUrl())
                    <img src="{{ $member->coverUrl() }}" alt="{{ $member->name }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                @endif
                <h2 class="font-display text-3xl text-charcoal mt-5">{{ $member->name }}</h2>
                <p class="text-sm text-muted mt-1">{{ $member->role }}</p>
                <p class="mt-3 text-muted leading-relaxed">{{ $member->bio }}</p>
            </div>
        @endforeach
    </div>
</section>
<x-page-cta heading="Travel with people who know Africa" />
@endsection
