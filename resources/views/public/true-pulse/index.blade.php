@extends('layouts.public')

@section('title', 'True Pulse | Pearl Pulse Safaris')
@section('meta_description', 'Africa through the eyes of our travellers — guest photography, stories, and moments shared with permission.')

@section('content')
<section class="bg-white pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow">True Pulse</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Africa through the eyes of our travellers</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Guest photography, reels, and stories — curated by our team. Instagram hashtag feeds will follow once the client confirms the integration approach; until then, this gallery is the living social proof.</p>
        @if(!empty($instagramUrl))
            <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="mt-8 inline-flex text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70">
                Follow on Instagram
            </a>
        @endif
    </div>
</section>

@if($items->isNotEmpty())
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($items as $item)
            <figure>
                @if($item->videoPlaybackUrl())
                    <video class="aspect-[4/5] w-full object-cover bg-forest" controls playsinline preload="none" poster="{{ $item->coverUrl() }}">
                        <source src="{{ $item->videoPlaybackUrl() }}" type="video/mp4">
                    </video>
                @elseif($item->coverUrl())
                    <img src="{{ $item->coverUrl() }}" alt="{{ $item->title ?: 'True Pulse' }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                @endif
                <figcaption class="mt-3">
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted">{{ $item->type }}</p>
                    @if($item->title)
                        <p class="font-display text-2xl text-charcoal mt-1">{{ $item->title }}</p>
                    @endif
                    <p class="text-sm text-muted mt-1">{{ $item->caption }}</p>
                    @if($item->guest_name)
                        <p class="text-sm text-charcoal mt-2">{{ $item->guest_name }}</p>
                    @endif
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>
@else
<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-muted">Stories are being gathered. Follow along on Instagram in the meantime.</p>
    </div>
</section>
@endif

<x-page-cta heading="Your journey could be here" text="Share your journey — we review every story before it appears." button="Share your journey" :href="route('plan')" />
@endsection
