@extends('layouts.public')

@section('title', $country->seoTitle())
@section('meta_description', \Illuminate\Support\Str::limit($country->seoDescription(), 160))

@section('content')
@php $cover = $country->coverUrl(); @endphp
<section class="relative min-h-[68svh] flex items-end overflow-hidden bg-forest">
    @if($cover)
        <img src="{{ $cover }}" alt="{{ $country->name }}" class="absolute inset-0 h-full w-full object-cover scale-105" data-parallax="0.1">
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-forest/90 via-forest/30 to-transparent"></div>
    <div class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 pt-12 lg:px-8">
        <x-breadcrumbs :items="[
            ['label' => 'Destinations', 'href' => route('destinations.index')],
            ['label' => $country->name],
        ]" />
        <h1 class="font-display text-6xl md:text-8xl text-sand mt-4 fade-up leading-[0.92]">{{ $country->name }}</h1>
        @if($country->subtitle)
            <p class="mt-4 text-lg md:text-xl text-sand/80 fade-up max-w-2xl" style="animation-delay: 0.12s">{{ $country->subtitle }}</p>
        @endif
    </div>
</section>

<section class="surface surface--white py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 split-editorial reveal">
        <div>
            <p class="section-eyebrow">Why {{ $country->name }}</p>
            <h2 class="font-display text-4xl md:text-5xl text-charcoal mt-2">Africa, deeply personal</h2>
            <div class="prose-safari mt-6">{!! $country->description !!}</div>
        </div>
        <div class="split-editorial__still">
            @if($cover)
                <img src="{{ $cover }}" alt="" loading="lazy" decoding="async">
            @endif
        </div>
    </div>
</section>

@if($country->journeys->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-24 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-8">
            <h2 class="font-display text-4xl text-charcoal">Featured journeys</h2>
            <a href="{{ route('journeys.country', $country) }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">All {{ $country->name }} journeys</a>
        </div>
        <div class="filmstrip reveal-stagger">
            @foreach($country->journeys as $journey)
                <x-journey-card :journey="$journey" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($experiences->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Experiences</h2>
        <div class="filmstrip reveal-stagger">
            @foreach($experiences as $experience)
                <x-experience-card :experience="$experience" />
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="surface surface--beige py-16 lg:py-24">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">Where to go</h2>
        <div class="grid gap-5 md:grid-cols-2 reveal-stagger">
            @foreach($country->destinations as $destination)
                <a href="{{ $destination->publicUrl() }}" class="stage-link min-h-[18rem] md:min-h-[22rem]">
                    @if($destination->coverUrl())
                        <img src="{{ $destination->coverThumbUrl() ?: $destination->coverUrl() }}" alt="{{ $destination->name }}" loading="lazy" decoding="async">
                    @endif
                    <div class="stage-link__shade"></div>
                    <div class="stage-link__copy">
                        <p class="text-[11px] tracking-[0.18em] uppercase text-white/70">{{ $destination->region ?: $country->name }}</p>
                        <h3 class="font-display text-3xl md:text-4xl text-white mt-1">{{ $destination->name }}</h3>
                        @if($destination->teaser)
                            <p class="mt-2 text-sm text-white/85 line-clamp-2 max-w-md">{{ $destination->teaser }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

@if($stays->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20 overflow-hidden">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal mb-8 max-w-2xl">
            <h2 class="font-display text-4xl text-charcoal">Selected stays</h2>
            <p class="mt-3 text-muted">Places we have selected — we do not own these lodges.</p>
        </div>
        <div class="editorial-rail reveal-stagger">
            @foreach($stays as $stay)
                <x-stay-card :stay="$stay" />
            @endforeach
        </div>
    </div>
</section>
@endif

@if($country->best_time || $country->practical)
<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        @if($country->best_time)
            <h2 class="font-display text-3xl text-charcoal">Best time</h2>
            <p class="mt-3 text-muted leading-relaxed">{{ $country->best_time }}</p>
        @endif
        @if($country->practical)
            <h2 class="font-display text-3xl text-charcoal {{ $country->best_time ? 'mt-10' : '' }}">Practical information</h2>
            <div class="prose-safari mt-4">{!! $country->practical !!}</div>
        @endif
    </div>
</section>
@endif

@if($pulse->isNotEmpty())
<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <div class="reveal flex flex-wrap items-end justify-between gap-4 mb-8">
            <h2 class="font-display text-4xl text-charcoal">True Pulse</h2>
            <a href="{{ route('true-pulse') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">Explore True Pulse</a>
        </div>
        <div class="pulse-mosaic reveal-stagger">
            @foreach($pulse as $item)
                <figure>
                    @if($item->coverUrl())
                        <img src="{{ $item->coverThumbUrl() ?: $item->coverUrl() }}" alt="{{ $item->title }}" loading="lazy" decoding="async">
                    @endif
                    @if($item->caption || $item->title)
                        <figcaption>{{ $item->caption ?: $item->title }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($faqs->isNotEmpty())
<section class="surface surface--beige py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <h2 class="font-display text-4xl text-charcoal mb-8 reveal">FAQs</h2>
        <dl class="space-y-8 reveal-stagger">
            @foreach($faqs as $faq)
                <div class="border-t border-charcoal/10 pt-6">
                    <dt class="font-display text-2xl text-charcoal">{{ $faq->question }}</dt>
                    <dd class="mt-2 text-muted leading-relaxed">{{ $faq->answer }}</dd>
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

<x-page-cta
    heading="Your {{ $country->name }} journey starts here"
    button="Plan a {{ $country->name }} journey"
/>
@endsection
