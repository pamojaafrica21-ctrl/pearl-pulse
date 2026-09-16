@props([
    'video' => null,
    'image' => null,
    'alt' => '',
])

@php
    $youtubeEmbed = \App\Support\VideoUrl::youtubeEmbedUrl($video);
    $youtubePoster = \App\Support\VideoUrl::youtubePosterUrl($video);
@endphp

@if($youtubeEmbed)
    <div class="hero-youtube absolute inset-0 overflow-hidden fade-in" aria-hidden="true">
        @if($youtubePoster || $image)
            <img
                src="{{ $image ?: $youtubePoster }}"
                alt=""
                class="absolute inset-0 h-full w-full object-cover"
            >
        @endif
        <iframe
            src="{{ $youtubeEmbed }}"
            class="hero-youtube__frame"
            title="{{ $alt ?: 'Background video' }}"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen
            loading="eager"
        ></iframe>
    </div>
@elseif($video)
    <video class="absolute inset-0 h-full w-full object-cover fade-in" autoplay muted loop playsinline preload="metadata" @if($image) poster="{{ $image }}" @endif>
        <source src="{{ $video }}" type="video/mp4">
    </video>
@elseif($image)
    <img src="{{ $image }}" alt="{{ $alt }}" class="absolute inset-0 h-full w-full object-cover fade-in" loading="eager" fetchpriority="high">
@endif
