@props([
    'class' => '',
])

<span {{ $attributes->class(['path-accent']) }} aria-hidden="true">
    <svg viewBox="0 0 120 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M8 36 C28 36 32 12 52 12 C72 12 76 36 96 36 C108 36 112 28 116 22"
            stroke="currentColor"
            stroke-width="1.5"
            stroke-dasharray="2 5"
            stroke-linecap="round"
        />
        <circle cx="116" cy="20" r="3.5" fill="currentColor" />
    </svg>
</span>
