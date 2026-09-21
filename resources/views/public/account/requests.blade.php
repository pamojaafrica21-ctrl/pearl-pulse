@extends('layouts.public')

@section('title', 'My requests | Pearl Pulse Safaris')
@section('meta_description', 'Your Journey Finder and planning requests with Pearl Pulse Safaris.')

@section('content')
<section class="bg-white">
    <x-account-nav active="requests" />
</section>

<section class="bg-cream py-14 lg:py-20">
    <div class="mx-auto max-w-7xl px-5 lg:px-8">
        @if($enquiries->isEmpty())
            <div class="max-w-xl">
                <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Empty for now</p>
                <h2 class="font-display text-3xl md:text-4xl text-charcoal mt-2">No requests yet</h2>
                <p class="mt-4 text-muted leading-relaxed">
                    When you submit the Journey Finder quiz or a plan request while signed in, it will appear here.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('journeys.finder') }}" class="btn-primary">Open Journey Finder</a>
                    <a href="{{ route('plan') }}" class="btn-outline-dark">Plan your journey</a>
                </div>
            </div>
        @else
            <div class="mb-10">
                <p class="text-[11px] tracking-[0.18em] uppercase text-muted">History</p>
                <p class="font-display text-2xl text-charcoal mt-1">
                    {{ $enquiries->count() }} {{ \Illuminate\Support\Str::plural('request', $enquiries->count()) }}
                </p>
            </div>

            <div class="space-y-4">
                @foreach($enquiries as $enquiry)
                    <article class="bg-white border border-charcoal/10 p-5 sm:p-6">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-[11px] tracking-[0.14em] uppercase text-muted">
                                    {{ $enquiry->created_at->format('d M Y · H:i') }}
                                    · {{ $enquiry->channelLabel() }}
                                </p>
                                <h2 class="font-display text-2xl text-forest mt-1">
                                    {{ $enquiry->booking?->reference ? $enquiry->booking->reference.' · ' : '' }}{{ $enquiry->journey?->name ?? (is_array($enquiry->preferred_destinations) && $enquiry->preferred_destinations !== [] ? implode(', ', $enquiry->preferred_destinations) : 'Custom journey request') }}
                                </h2>
                            </div>
                            <span class="text-[10px] tracking-[0.12em] uppercase px-2 py-1 border border-charcoal/15 text-muted">{{ $enquiry->guestStatusLabel() }}</span>
                        </div>

                        @if($enquiry->booking)
                            <p class="mt-3 text-sm text-charcoal">
                                Booking {{ $enquiry->booking->statusLabel() }}
                                @if($enquiry->booking->datesLabel())
                                    · {{ $enquiry->booking->datesLabel() }}
                                @endif
                            </p>
                        @endif

                        <div class="mt-4 grid gap-2 text-sm text-muted sm:grid-cols-2">
                            @if($enquiry->days)
                                <p>Duration: <span class="text-charcoal">{{ $enquiry->days }}</span></p>
                            @endif
                            @if($enquiry->travellers)
                                <p>Travellers: <span class="text-charcoal">{{ $enquiry->travellers }}</span></p>
                            @endif
                            @if($enquiry->travel_dates)
                                <p>When: <span class="text-charcoal">{{ $enquiry->travel_dates }}</span></p>
                            @endif
                            @if($enquiry->accommodation)
                                <p>Stay: <span class="text-charcoal">{{ $enquiry->accommodation }}</span></p>
                            @endif
                            @if($enquiry->preferred_experiences)
                                <p class="sm:col-span-2">Experiences: <span class="text-charcoal">{{ implode(', ', $enquiry->preferred_experiences) }}</span></p>
                            @endif
                        </div>

                        @if($enquiry->message)
                            <div class="mt-4 text-sm text-charcoal/80 leading-relaxed prose prose-sm max-w-none">
                                {!! $enquiry->message !!}
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>

            <div class="mt-12">
                <a href="{{ route('journeys.finder') }}" class="btn-primary">Start another quiz</a>
            </div>
        @endif
    </div>
</section>
@endsection
