@extends('layouts.public')

@section('title', config('app.name').' — East African Safaris')
@section('meta_description', $heroTagline)

@section('content')
<section class="relative min-h-[100svh] flex items-end overflow-hidden bg-forest">
    @if($heroVideoUrl)
        <video class="absolute inset-0 h-full w-full object-cover fade-in" autoplay muted loop playsinline poster="{{ $heroImageUrl }}">
            <source src="{{ $heroVideoUrl }}" type="video/mp4">
        </video>
    @elseif($heroImageUrl)
        <img src="{{ $heroImageUrl }}" alt="" class="absolute inset-0 h-full w-full object-cover fade-in">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/35 to-forest/20"></div>

    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-20 pt-40 lg:px-8 lg:pb-28">
        <p class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl text-sand leading-[0.95] max-w-4xl fade-up">
            Pearl Pulse Safaris
        </p>
        <p class="mt-6 max-w-xl text-sand/85 text-lg md:text-xl font-light leading-relaxed fade-up" style="animation-delay: 0.15s">
            {{ $heroTagline }}
        </p>
        <div class="mt-10 fade-up" style="animation-delay: 0.3s">
            <a href="{{ route('destinations.index') }}" class="btn-outline">Explore Destinations</a>
        </div>
    </div>
</section>

<section class="bg-cream py-20 lg:py-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Featured journeys</p>
            <h2 class="font-display text-4xl md:text-5xl text-forest">Destinations worth the voyage</h2>
        </div>

        <div class="mt-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($featured as $destination)
                <x-destination-card :destination="$destination" />
            @empty
                <p class="text-muted col-span-full">Destinations coming soon.</p>
            @endforelse
        </div>

        @if($featured->isNotEmpty())
            <div class="mt-14 text-center">
                <a href="{{ route('destinations.index') }}" class="btn-primary">View all destinations</a>
            </div>
        @endif
    </div>
</section>
@endsection
