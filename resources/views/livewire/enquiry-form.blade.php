<div>
    @if($submitted)
        <div class="rounded-sm border border-sand/30 bg-sand/10 px-5 py-4 text-sm">
            Thank you — we’ve received your enquiry and will be in touch shortly.
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div>
                <label for="enquiry-name" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Name</label>
                <input id="enquiry-name" type="text" wire:model="name" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="enquiry-email" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Email</label>
                    <input id="enquiry-email" type="email" wire:model="email" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="enquiry-phone" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Phone</label>
                    <input id="enquiry-phone" type="text" wire:model="phone" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="enquiry-message" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Message</label>
                <textarea id="enquiry-message" rows="5" wire:model="message" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"></textarea>
                @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Send enquiry</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>
