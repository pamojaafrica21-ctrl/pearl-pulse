@props([
    'video' => null,
    'image' => null,
    'alt' => '',
])

@if($video)
    <video class="absolute inset-0 h-full w-full object-cover fade-in" autoplay muted loop playsinline @if($image) poster="{{ $image }}" @endif>
        <source src="{{ $video }}" type="video/mp4">
    </video>
@elseif($image)
    <img src="{{ $image }}" alt="{{ $alt }}" class="absolute inset-0 h-full w-full object-cover fade-in">
@endif
