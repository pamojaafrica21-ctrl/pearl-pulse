@extends('layouts.public')

@section('title', 'Saved journeys | Pearl Pulse Safaris')
@section('meta_description', 'Your saved Pearl Pulse Safaris journeys.')

@section('content')
<section class="bg-white pt-16 pb-10">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow">Your account</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Saved journeys</h1>
        <p class="mt-4 max-w-2xl text-muted leading-relaxed">Favourites you have saved while exploring. When you are ready, plan a private proposal with us.</p>
    </div>
</section>

<section class="bg-cream pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        @if($journeys->isEmpty())
            <p class="text-muted">You have not saved any journeys yet.</p>
            <a href="{{ route('journeys.index') }}" class="btn-outline-dark mt-8 inline-flex">Browse journeys</a>
        @else
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($journeys as $journey)
                    <div>
                        <x-journey-card :journey="$journey" />
                        <div class="mt-4">
                            <livewire:favorite-button :journey="$journey" :key="'fav-'.$journey->id" />
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-14">
                <a href="{{ route('plan') }}" class="btn-primary">Plan your journey</a>
            </div>
        @endif
    </div>
</section>
@endsection
