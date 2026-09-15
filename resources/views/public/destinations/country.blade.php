@extends('layouts.public')

@section('title', $country->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($country->seoDescription(), 160))

@section('content')
@php $cover = $country->coverUrl(); @endphp
<section class="relative min-h-[60svh] flex items-end overflow-hidden bg-forest">
    @if($cover)
        <img src="{{ $cover }}" alt="{{ $country->name }}" class="absolute inset-0 h-full w-full object-cover scale-105" data-parallax="0.1">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Destinations', 'href' => route('destinations.index')],
            ['label' => $country->name],
        ]" />
        <h1 class="font-display text-5xl md:text-7xl text-sand mt-4 fade-up">{{ $country->name }}</h1>
        @if($country->subtitle)
            <p class="mt-3 text-lg text-sand/80 fade-up" style="animation-delay: 0.12s">{{ $country->subtitle }}</p>
        @endif
    </div>
</section>

<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <h2 class="font-display text-4xl text-charcoal">Why {{ $country->name }}?</h2>
        <div class="prose-safari mt-6">{!! $country->description !!}</div>
    </div>
</section>

@if($country->journeys->isNotEmpty())
<section class="bg-cream py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Featured journeys</h2>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4 reveal-stagger">
            @foreach($country->journeys as $journey)
                <div class="lift-card">
                    <x-journey-card :journey="$journey" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="bg-white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Where to go</h2>
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 reveal-stagger">
            @foreach($country->destinations as $destination)
                <div class="lift-card">
                    <x-destination-card :destination="$destination" />
                </div>
            @endforeach
        </div>
    </div>
</section>

@if($country->best_time || $country->practical)
<section class="bg-white pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        @if($country->best_time)
            <h2 class="font-display text-3xl text-charcoal">Best time</h2>
            <p class="mt-3 text-muted">{{ $country->best_time }}</p>
        @endif
        @if($country->practical)
            <h2 class="font-display text-3xl text-charcoal mt-10">Practical information</h2>
            <div class="prose-safari mt-4">{!! $country->practical !!}</div>
        @endif
    </div>
</section>
@endif

@if($pulse->isNotEmpty())
<section class="bg-white pb-16">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8">True Pulse</h2>
        <div class="grid gap-6 sm:grid-cols-3">
            @foreach($pulse as $item)
                <figure>
                    @if($item->coverUrl())
                        <img src="{{ $item->coverUrl() }}" alt="{{ $item->title }}" class="aspect-[4/5] w-full object-cover" loading="lazy">
                    @endif
                    <figcaption class="mt-2 text-sm text-muted">{{ $item->caption }}</figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($faqs->isNotEmpty())
<section class="bg-white pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8">FAQs</h2>
        <dl class="space-y-6">
            @foreach($faqs as $faq)
                <div>
                    <dt class="font-display text-2xl text-charcoal">{{ $faq->question }}</dt>
                    <dd class="mt-2 text-muted">{{ $faq->answer }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>
@push('head')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => $faqs->map(fn ($f) => [
        '@type' => 'Question',
        'name' => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
    ])->all(),
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
@endif

<x-page-cta heading="Your {{ $country->name }} journey starts here" button="Plan a {{ $country->name }} journey" />
@endsection
