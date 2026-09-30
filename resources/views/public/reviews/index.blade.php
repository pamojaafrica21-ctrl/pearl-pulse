@extends('layouts.public')

@section('title', 'Guest reviews | Pearl Pulse Safaris')
@section('meta_description', 'What guests remember about their Pearl Pulse journeys across East Africa.')

@section('content')
<section class="surface surface--beige py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mx-auto max-w-3xl text-center mb-12">
            <p class="section-eyebrow">Guest voices</p>
            <h1 class="font-display text-4xl md:text-5xl text-charcoal">The journeys our guests remember</h1>
            <x-review-platform-links
                :links="$reviewLinks ?? []"
                :show-lead="false"
                class="mt-8"
            />
        </div>

        <div class="guest-voices">
            @forelse($reviews as $review)
                <blockquote class="guest-voices__card reveal">
                    <p class="font-display text-2xl md:text-3xl text-charcoal leading-snug">“{{ $review->quote }}”</p>
                    <footer class="mt-6 pt-4 border-t border-forest/15 text-sm text-muted">
                        <span class="text-charcoal">{{ $review->guest_name }}</span>
                        @if($review->guest_country)
                            · {{ $review->guest_country }}
                        @endif
                        @if($review->journey)
                            · <a href="{{ route('journeys.show', $review->journey) }}" class="hover:text-forest transition">{{ $review->journey->name }}</a>
                        @endif
                    </footer>
                </blockquote>
            @empty
                <p class="text-muted text-center">Guest reviews will appear here soon.</p>
            @endforelse
        </div>

        <div class="mt-14 text-center">
            <a href="{{ route('plan') }}" class="btn-outline-dark">Plan your journey</a>
        </div>
    </div>
</section>
@endsection
