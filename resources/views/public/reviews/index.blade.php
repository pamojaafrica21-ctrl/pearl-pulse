@extends('layouts.public')

@section('title', 'Guest stories | Pearl Pulse Safaris')
@section('meta_description', 'Quotes and photographs from guests who travelled with Pearl Pulse across East Africa — plus links to Google and Tripadvisor reviews.')

@section('content')
<section class="surface surface--beige pt-16 pb-10 lg:pt-20">
    <div class="reveal mx-auto max-w-3xl px-5 lg:px-8 text-center">
        <p class="section-eyebrow">Guest stories</p>
        <h1 class="font-display text-4xl md:text-5xl text-charcoal">The journeys our guests remember</h1>
        <p class="mt-4 text-muted leading-relaxed">Words and photographs from private journeys — shared with permission. Read more on Google and Tripadvisor when you are ready.</p>
        <x-review-platform-links
            :links="$reviewLinks ?? []"
            :show-lead="false"
            class="mt-8"
        />
    </div>
</section>

<section class="surface surface--beige pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="guest-stories grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @forelse($reviews as $review)
                <blockquote class="guest-stories__card reveal {{ $review->coverUrl() ? 'guest-stories__card--photo' : '' }}">
                    @if($review->coverUrl())
                        <div class="guest-stories__media aspect-[4/3] overflow-hidden bg-forest/10 mb-6">
                            <img
                                src="{{ $review->coverThumbUrl() ?: $review->coverUrl() }}"
                                alt="{{ $review->guest_name }}"
                                class="h-full w-full object-cover"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    @endif
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
                <p class="text-muted text-center md:col-span-2 lg:col-span-3">Guest stories will appear here soon.</p>
            @endforelse
        </div>

        <div class="mt-14 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-outline-dark">Speak to a specialist</a>
            <a href="{{ route('plan') }}" class="btn-primary">Plan your journey</a>
        </div>
    </div>
</section>
@endsection
