@extends('layouts.public')

@section('title', 'True Pulse | Pearl Pulse Safaris')
@section('meta_description', 'Real guests. Real moments — live from Instagram. Posts and reels from Pearl Pulse Safaris.')

@section('content')
<section class="bg-beige pt-16 pb-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        <p class="section-eyebrow">True Pulse</p>
        <livewire:true-pulse-viewer :limit="24" />
    </div>
</section>

<x-page-cta heading="Your journey could be here" text="Share your journey — tag us on Instagram or write to us." button="Plan your journey" :href="route('plan')" />
@endsection
