@extends('layouts.public')

@section('title', 'FAQs | Pearl Pulse Safaris')
@section('meta_description', 'Answers to common questions about permits, timing, inclusions, families, and planning a Pearl Pulse journey.')

@section('content')
<section class="surface surface--beige py-16 lg:py-24">
    <div class="mx-auto max-w-3xl px-5 lg:px-8">
        <div class="reveal text-center mb-12">
            <p class="section-eyebrow">Practical answers</p>
            <h1 class="font-display text-4xl md:text-5xl text-charcoal">Frequently asked questions</h1>
            <p class="mt-4 text-muted leading-relaxed">From permits to payment — the questions travellers ask us most often.</p>
        </div>

        <div class="faq-list" x-data="{ open: null }">
            @forelse($faqs as $i => $faq)
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
            @empty
                <p class="text-muted text-center">FAQs will appear here soon.</p>
            @endforelse
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('plan') }}" class="btn-outline-dark">Still have a question? Write to us</a>
        </div>
    </div>
</section>
@endsection
