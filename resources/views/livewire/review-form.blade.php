<div class="border border-sand-deep/40 bg-white/60 p-6 md:p-8">
    @if($submitted)
        <div class="rounded-sm border border-sand/30 bg-sand/10 px-5 py-4 text-sm text-forest">
            Thank you — your review has been received. We’ll review it before it appears on the site.
        </div>
    @else
        <h3 class="font-display text-2xl text-forest">Share your experience</h3>
        <p class="mt-2 text-sm text-muted">Tell us about this journey. Reviews are moderated before they appear publicly.</p>

        <form wire:submit="submit" class="mt-6 space-y-5">
            <div class="absolute -left-[9999px] opacity-0" aria-hidden="true">
                <label for="review-website">Website</label>
                <input id="review-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="review-name" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Your name</label>
                    <input id="review-name" type="text" wire:model="guest_name" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                    @error('guest_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="review-country" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Country <span class="normal-case tracking-normal">(optional)</span></label>
                    <input id="review-country" type="text" wire:model="guest_country" placeholder="e.g. United Kingdom" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                    @error('guest_country') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="review-quote" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Your review</label>
                <textarea id="review-quote" rows="5" wire:model="quote" placeholder="What stood out about the guiding, pacing, or places?" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"></textarea>
                @error('quote') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Submit review</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>
