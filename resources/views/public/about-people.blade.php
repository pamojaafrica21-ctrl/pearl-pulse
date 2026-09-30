@extends('layouts.public')

@section('title', 'Our people | Pearl Pulse Safaris')
@section('meta_description', 'Meet the guides and planners behind Pearl Pulse — local specialists who know the ground and shape your private journey.')

@section('content')
<section class="surface surface--white pt-16 pb-12 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Our people</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal leading-tight">Your journey. Our people.</h1>
        <p class="mt-5 text-muted leading-relaxed text-lg max-w-2xl">The guides and planners behind Pearl Pulse are local, visible, and part of how your days unfold — from the first proposal to the forest floor.</p>
    </div>
</section>

<section class="surface surface--beige pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 pt-4 lg:pt-8 grid gap-12 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($team as $member)
            <article class="reveal">
                @if($member->coverUrl())
                    <img
                        src="{{ $member->coverUrl() }}"
                        alt="{{ $member->name }}"
                        class="aspect-[4/5] w-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                @else
                    <div class="aspect-[4/5] w-full bg-forest/10"></div>
                @endif
                <h2 class="font-display text-3xl text-charcoal mt-5">{{ $member->name }}</h2>
                @if($member->role)
                    <p class="text-[13px] tracking-[0.14em] uppercase text-muted mt-2">{{ $member->role }}</p>
                @endif
                @if($member->bio)
                    <p class="mt-3 text-muted leading-relaxed">{{ $member->bio }}</p>
                @endif
            </article>
        @empty
            <p class="text-muted">Team profiles will appear here soon.</p>
        @endforelse
    </div>
</section>

<x-page-cta
    heading="Travel with people who know Africa"
    text="Speak to a specialist — we will match you with the right guide and pace."
    button="Speak to a specialist"
    :href="route('contact')"
    :secondary="route('plan')"
    secondary-label="Plan your journey"
/>
@endsection
