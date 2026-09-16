@extends('layouts.public')

@section('title', 'Saved journeys | Pearl Pulse Safaris')
@section('meta_description', 'Your saved Pearl Pulse Safaris journeys.')

@section('content')
<section class="bg-white">
    <x-account-nav active="favorites" />
</section>

<section class="bg-cream py-14 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        @if($journeys->isEmpty())
            <div class="grid gap-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-5">
                    <div class="relative overflow-hidden bg-forest aspect-[4/5] sm:aspect-[5/4] lg:aspect-[4/5]">
                        @if(!empty($inspireImage))
                            <img src="{{ $inspireImage }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-90">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-forest via-forest-light to-[#3d4f35]"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-charcoal/70 via-charcoal/20 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            <p class="text-[11px] tracking-[0.18em] uppercase text-white/70">Start here</p>
                            <p class="font-display text-3xl text-white mt-2 leading-tight">Save journeys that speak to you</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-6 lg:col-start-7">
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Empty for now</p>
                    <h2 class="font-display text-3xl md:text-4xl text-charcoal mt-2">You have not saved any journeys yet</h2>
                    <p class="mt-4 text-muted leading-relaxed max-w-lg">
                        Browse signature itineraries and tap <span class="text-charcoal">Save journey</span> on any that feel right.
                        Your shortlist will live here until you are ready to plan.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('journeys.index') }}" class="btn-primary">Browse journeys</a>
                        <a href="{{ route('journeys.finder') }}" class="btn-outline-dark">Open the finder</a>
                    </div>
                    <ul class="mt-10 space-y-3 text-sm text-muted">
                        <li class="flex gap-3"><span class="text-forest">01</span> Save journeys while you explore</li>
                        <li class="flex gap-3"><span class="text-forest">02</span> Compare pace, places, and duration</li>
                        <li class="flex gap-3"><span class="text-forest">03</span> Tell us what you loved — we tailor from there</li>
                    </ul>
                </div>
            </div>
        @else
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Shortlist</p>
                    <p class="font-display text-2xl text-charcoal mt-1">
                        {{ $journeys->count() }} {{ \Illuminate\Support\Str::plural('journey', $journeys->count()) }} saved
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('journeys.index') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70 transition">Browse more →</a>
                    <a href="{{ route('plan') }}" class="btn-primary !py-2.5 !px-5 text-[11px]">Plan from these</a>
                </div>
            </div>

            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($journeys as $journey)
                    <div class="group/account-card">
                        <x-journey-card :journey="$journey" />
                        <div class="mt-4 flex items-center justify-between gap-3">
                            <livewire:favorite-button :journey="$journey" :key="'fav-'.$journey->id" />
                            <a href="{{ route('plan', ['journey' => $journey->slug]) }}" class="text-[11px] tracking-[0.14em] uppercase text-muted hover:text-forest transition">
                                Enquire →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-16 border border-charcoal/10 bg-white p-8 sm:p-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Next step</p>
                    <h2 class="font-display text-3xl text-charcoal mt-2">Ready to shape a private proposal?</h2>
                    <p class="mt-2 text-sm text-muted max-w-lg">Share what you saved — we will refine pace, parks, and stays around how you want to travel.</p>
                </div>
                <a href="{{ route('plan') }}" class="btn-primary shrink-0">Plan your journey</a>
            </div>
        @endif
    </div>
</section>
@endsection
