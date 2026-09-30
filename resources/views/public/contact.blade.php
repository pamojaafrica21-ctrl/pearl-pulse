@extends('layouts.public')

@section('title', 'Speak to a specialist | Pearl Pulse Safaris')
@section('meta_description', 'Contact Pearl Pulse Safaris in Kampala — enquiry form, WhatsApp, phone, and email. We typically reply within a few hours.')

@section('content')
<section class="surface surface--white pt-16 pb-10 lg:pt-20">
    <div class="mx-auto max-w-3xl px-5 lg:px-8 reveal">
        <p class="section-eyebrow">Speak to a specialist</p>
        <h1 class="font-display text-5xl md:text-6xl text-charcoal leading-tight">Let’s talk about your journey</h1>
        <p class="mt-5 text-muted leading-relaxed text-lg">Write to us, call, or message on WhatsApp. We typically reply within a few hours from Uganda — often sooner.</p>
        <p class="mt-4 text-sm text-muted">
            Prefer a guided planner?
            <a href="{{ route('plan') }}" class="text-forest hover:opacity-70 transition">Plan your journey</a>
            step by step.
        </p>
    </div>
</section>

<section class="surface surface--beige pb-20 lg:pb-28">
    <div class="mx-auto max-w-7xl px-5 lg:px-8 pt-4 lg:pt-8 grid gap-14 lg:grid-cols-12 lg:items-stretch">
        <div class="lg:col-span-5 reveal flex flex-col gap-10 min-h-0">
            <div class="shrink-0">
                <p class="section-eyebrow">Reach us</p>
                <div class="mt-6 space-y-5 text-muted">
                    @if(!empty($contact['address']))
                        <div>
                            <p class="text-[12px] tracking-[0.16em] uppercase text-charcoal/50 mb-1">Address</p>
                            <p class="text-charcoal whitespace-pre-line leading-relaxed">{{ $contact['address'] }}</p>
                        </div>
                    @endif
                    @if(!empty($contact['phone']))
                        <div>
                            <p class="text-[12px] tracking-[0.16em] uppercase text-charcoal/50 mb-1">Phone</p>
                            <p>
                                <a class="text-charcoal hover:text-forest transition" href="tel:{{ preg_replace('/\s+/', '', $contact['phone']) }}">{{ $contact['phone'] }}</a>
                            </p>
                        </div>
                    @endif
                    @if(!empty($contact['email']))
                        <div>
                            <p class="text-[12px] tracking-[0.16em] uppercase text-charcoal/50 mb-1">Email</p>
                            <p>
                                <a class="text-charcoal hover:text-forest transition" href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                            </p>
                        </div>
                    @endif
                    @if(!empty($whatsappUrl))
                        <div>
                            <p class="text-[12px] tracking-[0.16em] uppercase text-charcoal/50 mb-1">WhatsApp</p>
                            <p>
                                <a class="text-forest hover:opacity-70 transition" href="{{ $whatsappUrl }}" target="_blank" rel="noopener">Message us from Uganda</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="shrink-0 rounded-sm border border-forest/10 bg-white/50 px-5 py-4">
                <p class="text-sm text-charcoal leading-relaxed">
                    <span class="font-medium">We reply within a few hours</span>
                    <span class="text-muted"> during Uganda business hours — evenings and weekends when we can. Urgent permit or lodge questions are welcome anytime.</span>
                </p>
            </div>

            @if(!empty($mapEmbed))
                <div class="contact-map mt-auto min-h-[16rem] flex-1 overflow-hidden border border-charcoal/8 bg-sand/40 lg:min-h-0">
                    <iframe
                        src="{{ $mapEmbed }}"
                        title="Pearl Pulse Safaris location map"
                        class="h-full w-full min-h-[16rem] border-0 lg:min-h-full"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            @endif
        </div>

        <div class="lg:col-span-7 reveal h-full">
            <div class="h-full">
                <livewire:enquiry-form variant="contact" />
            </div>
        </div>
    </div>
</section>
@endsection
