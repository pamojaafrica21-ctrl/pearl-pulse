@extends('layouts.public')

@section('title', $page->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($page->seoDescription(), 160))

@section('content')
@php
    $legalLinks = [
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms & Conditions',
        'cancellation' => 'Cancellation',
        'cookies' => 'Cookie Policy',
    ];
@endphp
<section class="surface surface--white pt-16 pb-20 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <div class="reveal">
            <p class="section-eyebrow">Legal</p>
            <h1 class="font-display text-5xl text-charcoal">{{ $page->title }}</h1>
            <p class="mt-3 text-sm text-muted">Last updated {{ $page->updated_at?->format('j F Y') ?? '—' }}</p>
        </div>

        <nav class="mt-8 flex flex-wrap gap-x-5 gap-y-2 text-sm border-b border-charcoal/10 pb-6" aria-label="Legal policies">
            @foreach($legalLinks as $slug => $label)
                <a
                    href="{{ route('legal', $slug) }}"
                    class="{{ $page->slug === $slug ? 'text-forest' : 'text-muted hover:text-forest' }} transition"
                    @if($page->slug === $slug) aria-current="page" @endif
                >{{ $label }}</a>
            @endforeach
        </nav>

        <div class="prose-safari mt-10 reveal">{!! $page->content !!}</div>
    </div>
</section>
@endsection
