@props([
    'links' => null,
    'lead' => 'Read more reviews — or leave your own — on the platforms travellers already trust.',
    'showLead' => true,
])

@php
    $links = is_array($links) ? $links : ($reviewLinks ?? []);
    $google = trim((string) ($links['google'] ?? ''));
    $tripadvisor = trim((string) ($links['tripadvisor'] ?? ''));
@endphp

@if($google !== '' || $tripadvisor !== '')
    <div {{ $attributes->class([
        'review-platforms',
        'review-platforms--compact' => ! $showLead,
    ]) }}>
        @if($showLead && $lead)
            <p class="review-platforms__lead">{{ $lead }}</p>
        @endif
        <div class="review-platforms__actions">
            @if($google !== '')
                <a
                    href="{{ $google }}"
                    class="review-platform review-platform--google"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Read and leave Pearl Pulse reviews on Google"
                >
                    <span class="review-platform__logo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="28" height="28" focusable="false">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1Z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23Z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18A10.96 10.96 0 0 0 1 12c0 1.77.42 3.45 1.18 4.93l3.66-2.84Z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53Z"/>
                        </svg>
                    </span>
                    <span class="review-platform__copy">
                        <span class="review-platform__name">Google</span>
                        <span class="review-platform__meta">Reviews</span>
                    </span>
                </a>
            @endif

            @if($tripadvisor !== '')
                <a
                    href="{{ $tripadvisor }}"
                    class="review-platform review-platform--tripadvisor"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="Read and leave Pearl Pulse reviews on Tripadvisor"
                >
                    <span class="review-platform__logo" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="28" height="28" focusable="false">
                            <circle cx="12" cy="12" r="11" fill="#34E0A1"/>
                            <circle cx="8.2" cy="11.2" r="3.1" fill="#fff"/>
                            <circle cx="15.8" cy="11.2" r="3.1" fill="#fff"/>
                            <circle cx="8.2" cy="11.2" r="1.15" fill="#000"/>
                            <circle cx="15.8" cy="11.2" r="1.15" fill="#000"/>
                            <path fill="#000" d="M12 6.2 10.85 8.9h2.3L12 6.2Zm-5.9 8.55c.55.95 1.55 1.6 2.7 1.75l.35-1.55a1.9 1.9 0 0 1-1.55-1.05L6.1 14.75Zm11.8 0-1.5-.85a1.9 1.9 0 0 1-1.55 1.05l.35 1.55c1.15-.15 2.15-.8 2.7-1.75Z"/>
                        </svg>
                    </span>
                    <span class="review-platform__copy">
                        <span class="review-platform__name">Tripadvisor</span>
                        <span class="review-platform__meta">Reviews</span>
                    </span>
                </a>
            @endif
        </div>
    </div>
@endif
