@props([
    'rating' => null,
])

@if($rating !== null)
    @php $filled = (int) round((float) $rating); @endphp
    <span class="review-stars" role="img" aria-label="Rated {{ number_format((float) $rating, 1) }} out of 5">
        @for($i = 1; $i <= 5; $i++)
            <svg viewBox="0 0 24 24" class="review-stars__star {{ $i <= $filled ? 'is-filled' : '' }}" focusable="false" aria-hidden="true">
                <path d="M12 2.5l2.85 6.15 6.65.72-4.95 4.55 1.35 6.55L12 17.2l-5.9 3.27 1.35-6.55L2.5 9.37l6.65-.72L12 2.5z"/>
            </svg>
        @endfor
    </span>
@endif
