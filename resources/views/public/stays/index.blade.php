@extends('layouts.public')

@section('title', 'Preferred lodges & camps | Pearl Pulse Safaris')
@section('meta_description', 'Curated lodges and camps we work with across East Africa. We do not own stays — we choose them for location, character, and fit.')

@section('content')
<section class="surface surface--beige pt-16 pb-12 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <h1 class="font-display text-5xl md:text-6xl text-charcoal leading-tight">
            @if($country)
                Stays in {{ $country->name }}
            @else
                Places we have selected
            @endif
        </h1>
        <p class="mt-5 text-muted leading-relaxed text-lg">
            @if($country)
                Lodges and camps we choose in {{ $country->name }} for location, character, and how they sit in the day. We do not own these stays.
            @else
                We do not own lodges. We choose stays for location, character, and how they sit in the day — forest-edge after a trek, a conservancy camp for fewer vehicles, a highland night before the briefing.
            @endif
        </p>
        @if($country)
            <p class="mt-4">
                <a href="{{ route('stays.index') }}" class="home-text-link">All preferred lodges →</a>
            </p>
        @endif
    </div>
</section>

<section class="surface surface--beige pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 pt-4 lg:pt-8 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($stays as $stay)
            <div class="reveal">
                <x-stay-card :stay="$stay" />
            </div>
        @empty
            <p class="text-muted">Selected stays will appear here soon.</p>
        @endforelse
    </div>
</section>

<x-page-cta heading="Tell us how you like to stay" text="Essential, premium, or signature — we will match the journey." />
@endsection
