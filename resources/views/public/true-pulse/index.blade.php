@extends('layouts.public')

@section('title', 'True Pulse | Pearl Pulse Safaris')
@section('meta_description', 'Africa through the eyes of our travellers — guest photography, stories, and moments shared with permission.')

@section('content')
<section class="bg-cream pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">True Pulse</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">Africa through the eyes of our travellers</h1>
        <p class="mt-4 max-w-2xl text-muted">Guest photography, reels, and stories — moderated, never an automatic dump of every hashtag.</p>
    </div>
</section>
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($items as $item)
            <figure>
                @if($item->coverUrl())
                    <img src="{{ $item->coverUrl() }}" alt="{{ $item->title ?: 'True Pulse' }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                @endif
                <figcaption class="mt-3">
                    <p class="text-[11px] tracking-[0.18em] uppercase text-gold">{{ $item->type }}</p>
                    @if($item->title)
                        <p class="font-display text-2xl text-forest mt-1">{{ $item->title }}</p>
                    @endif
                    <p class="text-sm text-muted mt-1">{{ $item->caption }}</p>
                    @if($item->guest_name)
                        <p class="text-sm text-forest mt-2">{{ $item->guest_name }}</p>
                    @endif
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>
<x-page-cta heading="Your journey could be here" text="Share your journey — we review every story before it appears." button="Share your journey" :href="route('plan')" />
@endsection
