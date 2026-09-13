@extends('layouts.public')

@section('title', 'Page not found | Pearl Pulse Safaris')

@section('content')
<section class="bg-cream pt-16 pb-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 text-center">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">404</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">This path does not exist</h1>
        <p class="mt-4 text-muted">The journey you were looking for may have moved. Start from home, or tell us where you wanted to go.</p>
        <div class="mt-10 flex flex-wrap justify-center gap-4">
            <a href="{{ route('home') }}" class="btn-primary">Home</a>
            <a href="{{ route('plan') }}" class="btn-outline-dark">Plan your journey</a>
        </div>
    </div>
</section>
@endsection
