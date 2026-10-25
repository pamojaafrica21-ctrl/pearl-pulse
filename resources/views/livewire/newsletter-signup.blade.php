<div class="footer-newsletter">
    <p class="footer-newsletter__eyebrow">Newsletter</p>
    <p class="footer-newsletter__lead">Journey notes &amp; seasonal ideas from East Africa.</p>

    @if($submitted)
        <p class="footer-newsletter__success" role="status">Thank you — you are on the list.</p>
    @else
        <form wire:submit="subscribe" class="footer-newsletter__form">
            <label class="sr-only" for="newsletter-email-{{ $this->getId() }}">Email address</label>
            <input
                id="newsletter-email-{{ $this->getId() }}"
                type="email"
                wire:model="email"
                autocomplete="email"
                placeholder="Your email"
                class="footer-newsletter__input"
                required
            >
            <button type="submit" class="footer-newsletter__btn" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="subscribe">Subscribe</span>
                <span wire:loading wire:target="subscribe">…</span>
            </button>
        </form>
        @error('email')
            <p class="footer-newsletter__error">{{ $message }}</p>
        @enderror
    @endif
</div>
