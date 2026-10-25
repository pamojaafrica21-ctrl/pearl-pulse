@props([
    'platform' => '',
    'url' => '',
])

@php
    $key = strtolower((string) $platform);

    $label = match ($key) {
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'twitter', 'x' => 'X',
        'youtube' => 'YouTube',
        'tiktok' => 'TikTok',
        'linkedin' => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'rednote', 'xiaohongshu' => 'RedNote',
        default => ucfirst($platform),
    };
@endphp

<a
    href="{{ $url }}"
    target="_blank"
    rel="noopener"
    aria-label="Pearl Pulse Safaris on {{ $label }}"
    title="{{ $label }}"
    {{ $attributes->class(['footer-social']) }}
>
    @if($key === 'instagram')
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
            <rect x="3" y="3" width="18" height="18" rx="5"/>
            <circle cx="12" cy="12" r="4"/>
            <circle cx="17.2" cy="6.8" r="0.6" fill="currentColor" stroke="none"/>
        </svg>
    @elseif($key === 'facebook')
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M13.5 21v-6.9h2.35l.4-2.95H13.5V9.25c0-.86.28-1.5 1.55-1.5h1.35V5.12c-.3-.04-1.2-.12-2.25-.12-2.28 0-3.85 1.4-3.85 3.95v2.2H7.9v2.95h2.4V21h3.2z"/>
        </svg>
    @elseif(in_array($key, ['twitter', 'x'], true))
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M18.9 2H22l-6.77 7.74L23.2 22h-6.27l-4.9-6.4L6.4 22H3.28l7.24-8.28L2.8 2h6.43l4.43 5.85L18.9 2zm-1.1 18.13h1.73L7.7 3.8H5.85L17.8 20.13z"/>
        </svg>
    @elseif($key === 'youtube')
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M23.5 7.2a3 3 0 0 0-2.1-2.1C19.5 4.6 12 4.6 12 4.6s-7.5 0-9.4.5A3 3 0 0 0 .5 7.2 31.4 31.4 0 0 0 0 12a31.4 31.4 0 0 0 .5 4.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31.4 31.4 0 0 0 24 12a31.4 31.4 0 0 0-.5-4.8zM9.8 15.5v-7l6.3 3.5-6.3 3.5z"/>
        </svg>
    @elseif($key === 'tiktok')
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M19.6 7.2a6.3 6.3 0 0 1-3.7-1.2v8.1a5.6 5.6 0 1 1-4.8-5.5v2.5a3.1 3.1 0 1 0 2.2 3v-11h2.5c.3 1.5 1.4 2.8 2.9 3.4v2.7a6.5 6.5 0 0 1-.9-.1z"/>
        </svg>
    @elseif($key === 'linkedin')
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M4.98 3.5A2.5 2.5 0 1 1 5 8.5a2.5 2.5 0 0 1-.02-5zM3.5 9.75h3V21h-3V9.75zM9.75 9.75h2.87v1.54h.04c.4-.76 1.38-1.56 2.84-1.56 3.04 0 3.6 2 3.6 4.6V21h-3v-5.1c0-1.22-.02-2.78-1.7-2.78-1.7 0-1.96 1.33-1.96 2.7V21h-3V9.75z"/>
        </svg>
    @elseif($key === 'pinterest')
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M12 2C6.5 2 2 6.4 2 11.9c0 4.2 2.6 7.8 6.3 9-.1-.8-.2-2 0-2.9.2-.8 1.3-5.4 1.3-5.4s-.3-.7-.3-1.6c0-1.5.9-2.7 2-2.7.9 0 1.4.7 1.4 1.5 0 .9-.6 2.3-.9 3.5-.3 1.1.5 1.9 1.5 1.9 1.8 0 3.1-2.3 3.1-5.1 0-2.1-1.4-3.7-4-3.7-2.9 0-4.7 2.2-4.7 4.6 0 .9.3 1.8.7 2.3.1.1.1.2.1.3l-.3 1.1c0 .2-.2.2-.4.1-1.4-.6-2.1-2.2-2.1-4 0-3 2.5-6.6 7.5-6.6 4 0 6.6 2.9 6.6 6 0 4.1-2.3 7.1-5.6 7.1-1.1 0-2.2-.6-2.5-1.3l-.7 2.6c-.2.9-.9 2-1.3 2.7A10.1 10.1 0 0 0 12 22c5.5 0 10-4.5 10-10S17.5 2 12 2z"/>
        </svg>
    @elseif(in_array($key, ['rednote', 'xiaohongshu'], true))
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
            <path d="M5.5 3.5h13A2.5 2.5 0 0 1 21 6v12a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 18V6a2.5 2.5 0 0 1 2.5-2.5zm1.2 4.2v1.7h4.1V16h2.1V9.4h4.1V7.7H6.7z"/>
        </svg>
    @else
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
            <circle cx="12" cy="12" r="9"/>
            <path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>
        </svg>
    @endif
</a>
