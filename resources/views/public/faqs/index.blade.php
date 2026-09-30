@extends('layouts.public')

@section('title', 'FAQs | Pearl Pulse Safaris')
@section('meta_description', 'Answers to common questions about permits, timing, inclusions, families, payment, health, and visas for Pearl Pulse journeys.')

@section('content')
<section class="surface surface--beige pt-16 pb-10 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal text-center">
        <p class="section-eyebrow">Practical answers</p>
        <h1 class="font-display text-4xl md:text-5xl text-charcoal">Frequently asked questions</h1>
        <p class="mt-4 text-muted leading-relaxed">From permits to payment — the questions travellers ask us most often, grouped by topic.</p>
    </div>
</section>

<section class="surface surface--beige pb-20 lg:pb-28">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 space-y-14">
        @forelse($grouped as $topic => $faqs)
            <div class="reveal" x-data="{ open: null }">
                <h2 class="font-display text-2xl md:text-3xl text-charcoal mb-6">{{ $topicLabels[$topic] ?? ucfirst(str_replace('_', ' ', $topic)) }}</h2>
                <div class="faq-list">
                    @foreach($faqs as $i => $faq)
                        <div class="faq-item">
                            <button
                                type="button"
                                class="faq-item__trigger"
                                @click="open = open === {{ $i }} ? null : {{ $i }}"
                                :aria-expanded="(open === {{ $i }}).toString()"
                            >
                                <span>{{ $faq->question }}</span>
                                <span class="faq-item__icon" x-text="open === {{ $i }} ? '−' : '+'" aria-hidden="true"></span>
                            </button>
                            <div class="faq-item__panel" x-show="open === {{ $i }}" x-cloak>
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <p class="text-muted text-center">FAQs will appear here soon.</p>
        @endforelse

        <div class="text-center pt-4">
            <a href="{{ route('contact') }}" class="btn-outline-dark">Still have a question? Write to us</a>
        </div>
    </div>
</section>
@endsection
