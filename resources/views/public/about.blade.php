@extends('layouts.public')

@section('title', $title.' — '.config('app.name'))
@section('meta_description', $lead ?: 'Learn about Pearl Pulse Safaris and our approach to East African travel.')

@section('content')
<div class="pt-16 pb-16 bg-cream">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        @if($eyebrow)
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display text-5xl md:text-6xl text-forest">{{ $title }}</h1>
        @if($lead)
            <p class="mt-6 text-lg text-muted leading-relaxed">{{ $lead }}</p>
        @endif
        <div class="prose-safari mt-10">
            {!! $content !!}
        </div>
    </div>
</div>

@if(count($values))
<section class="bg-sand/35 py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            @foreach($values as $value)
                <div>
                    <h2 class="font-display text-2xl md:text-3xl text-forest">{{ $value['title'] }}</h2>
                    @if($value['text'])
                        <p class="mt-3 text-sm text-muted leading-relaxed">{{ $value['text'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($approachHeading || $approachBody)
<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        @if($approachHeading)
            <h2 class="font-display text-3xl md:text-4xl text-forest">{{ $approachHeading }}</h2>
        @endif
        @if($approachBody)
            <p class="mt-5 text-muted leading-relaxed whitespace-pre-line">{{ $approachBody }}</p>
        @endif
    </div>
</section>
@endif

@if(($team ?? collect())->isNotEmpty())
<section class="bg-cream py-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-forest mb-8">Our people</h2>
        <div class="grid gap-8 sm:grid-cols-3">
            @foreach($team as $member)
                <div>
                    @if($member->coverUrl())
                        <img src="{{ $member->coverUrl() }}" alt="{{ $member->name }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                    @endif
                    <h3 class="font-display text-2xl text-forest mt-4">{{ $member->name }}</h3>
                    <p class="text-sm text-gold">{{ $member->role }}</p>
                </div>
            @endforeach
        </div>
        <a href="{{ route('our-people') }}" class="inline-block mt-8 text-sm tracking-[0.14em] uppercase text-forest">Meet the team</a>
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
            <a href="{{ route('plan') }}" class="btn-outline">Plan your journey</a>
        </div>
    </div>
</section>
@endif
@endsection
