@extends('layouts.public')

@section('title', 'Insiders | Pearl Pulse Safaris')
@section('meta_description', 'Travel guides, practical notes, and answers from the field — for planning an East African safari.')

@section('content')
<section class="bg-white pt-16 pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="text-xs tracking-[0.22em] uppercase text-muted mb-3">Insiders</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal">Guides from the ground</h1>
        <div class="mt-6 flex flex-wrap gap-4 text-sm">
            <a href="{{ route('insiders.index') }}" class="{{ $type === '' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }}">All</a>
            <a href="{{ route('insiders.index', ['type' => 'guide']) }}" class="{{ $type === 'guide' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }}">Guides</a>
            <a href="{{ route('insiders.index', ['type' => 'practical']) }}" class="{{ $type === 'practical' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }}">Practical</a>
            <a href="{{ route('insiders.index', ['type' => 'field']) }}" class="{{ $type === 'field' ? 'text-charcoal' : 'text-muted hover:text-charcoal' }}">From the Field</a>
        </div>
    </div>
</section>
<section class="bg-white pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 grid gap-10 md:grid-cols-2">
        @foreach($articles as $article)
            <a href="{{ route('insiders.show', $article) }}" class="block group border-t border-charcoal/10 pt-6">
                <p class="text-[11px] tracking-[0.18em] uppercase text-muted">{{ $article->typeLabel() }}</p>
                <h2 class="font-display text-3xl text-charcoal mt-2 group-hover:text-forest">{{ $article->title }}</h2>
                <p class="mt-3 text-muted">{{ $article->excerpt }}</p>
            </a>
        @endforeach
    </div>
</section>
<x-page-cta heading="Still planning?" text="Ask us about your journey." />
@endsection
