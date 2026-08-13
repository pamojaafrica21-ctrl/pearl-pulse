@extends('layouts.public')

@section('title', $title.' — '.config('app.name'))
@section('meta_description', 'Learn about Pearl Pulse Safaris and our approach to East African travel.')

@section('content')
<div class="pt-28 pb-20 bg-cream">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Our story</p>
        <h1 class="font-display text-5xl md:text-6xl text-forest">{{ $title }}</h1>
        <div class="prose-safari mt-10">
            {!! $content !!}
        </div>
    </div>
</div>
@endsection
