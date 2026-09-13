@extends('layouts.public')

@section('title', $article->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($article->seoDescription(), 160))

@section('content')
<article class="bg-cream pt-16 pb-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <x-breadcrumbs tone="dark" :items="[
            ['label' => 'Insiders', 'href' => route('insiders.index')],
            ['label' => $article->title],
        ]" />
        <p class="text-xs tracking-[0.22em] uppercase text-gold mt-6">{{ $article->typeLabel() }}</p>
        <h1 class="font-display text-4xl md:text-6xl text-forest mt-3">{{ $article->title }}</h1>
        @if($article->excerpt)
            <p class="mt-4 text-lg text-muted">{{ $article->excerpt }}</p>
        @endif
        @if($article->coverUrl())
            <img src="{{ $article->coverUrl() }}" alt="{{ $article->title }}" class="mt-10 w-full aspect-[16/9] object-cover">
        @endif
        <div class="prose-safari mt-10">{!! $article->body !!}</div>
    </div>
</article>
@if($more->isNotEmpty())
<section class="bg-cream pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-3xl text-forest mb-6">More from Insiders</h2>
        <div class="space-y-4">
            @foreach($more as $item)
                <a href="{{ route('insiders.show', $item) }}" class="block text-forest hover:text-gold">{{ $item->title }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif
<x-page-cta heading="Still planning?" text="Ask us about your journey." />
@endsection
