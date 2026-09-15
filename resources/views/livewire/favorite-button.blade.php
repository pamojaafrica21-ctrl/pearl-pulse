<button
    type="button"
    wire:click="toggle"
    class="inline-flex items-center gap-2 text-[11px] tracking-[0.14em] uppercase transition {{ $saved ? 'text-forest' : 'text-muted hover:text-forest' }}"
    aria-pressed="{{ $saved ? 'true' : 'false' }}"
>
    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="{{ $saved ? 'currentColor' : 'none' }}" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-7-4.5-9.5-8.5C.5 9 2.5 5.5 6.2 5.5c2 0 3.3 1.2 3.8 2.1.5-.9 1.8-2.1 3.8-2.1 3.7 0 5.7 3.5 3.7 7C19 16.5 12 21 12 21z"/>
    </svg>
    <span>{{ $saved ? 'Saved' : 'Save journey' }}</span>
</button>
