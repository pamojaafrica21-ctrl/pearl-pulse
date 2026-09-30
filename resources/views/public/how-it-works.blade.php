@extends('layouts.public')

@section('title', 'How it works | Pearl Pulse Safaris')
@section('meta_description', 'A clear five-step process: tell us what you want, we design a proposal, refine together, secure with a deposit, and we handle everything.')

@section('content')
<section class="surface surface--white pt-16 pb-12 lg:pt-20 lg:pb-16">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal text-center">
        <p class="section-eyebrow">How it works</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal leading-tight">From first note to the forest floor</h1>
        <p class="mt-5 text-muted leading-relaxed text-lg">A private journey with Pearl Pulse follows five clear steps. No catalogues to navigate — a conversation, a proposal, and quiet logistics until you look up.</p>
    </div>
</section>

@php
    $steps = [
        [
            'mark' => '01',
            'title' => 'Tell us what you want',
            'text' => 'Share dates, travellers, and the feeling you are after — primates, open plains, honeymoon quiet, family pace, or something still unnamed. A short note or the Journey Planner is enough to begin.',
        ],
        [
            'mark' => '02',
            'title' => 'We design a proposal',
            'text' => 'We reply with a clear outline: destinations, nights, a sense of daily rhythm, and honest notes on season and investment. Not a generic brochure — a first sketch of your journey.',
        ],
        [
            'mark' => '03',
            'title' => 'Refine together',
            'text' => 'Adjust lodges, add a boat day, soften a transfer, protect photography light. We iterate until the shape feels right — then lock the detail you care about most.',
        ],
        [
            'mark' => '04',
            'title' => 'Secure with deposit',
            'text' => 'Once you are ready, a deposit holds permits and lodge nights against their deadlines. Terms are written clearly before you pay — including cancellation windows.',
        ],
        [
            'mark' => '05',
            'title' => 'We handle everything',
            'text' => 'Permits, transfers, briefings, and on-ground care. You arrive ready to look up — not down at a checklist. We stay reachable before and during travel.',
        ],
    ];
@endphp

<section class="surface surface--beige pb-16 lg:pb-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <ol class="how-steps">
            @foreach($steps as $i => $step)
                <li class="how-steps__item reveal">
                    <span class="how-steps__mark" aria-hidden="true">{{ $step['mark'] }}</span>
                    <div class="how-steps__body">
                        <h2 class="font-display text-2xl md:text-3xl text-charcoal">{{ $step['title'] }}</h2>
                        <p class="mt-3 text-muted leading-relaxed">{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="surface surface--white py-16 lg:py-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal text-center">
        <p class="section-eyebrow">Ready when you are</p>
        <h2 class="font-display text-3xl md:text-4xl text-charcoal">Start with a conversation</h2>
        <p class="mt-4 text-muted leading-relaxed">Write to a specialist, or use the step-by-step planner if you already know your countries and dates.</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-primary">Speak to a specialist</a>
            <a href="{{ route('plan') }}" class="btn-outline-dark">Plan your journey</a>
        </div>
    </div>
</section>
@endsection
