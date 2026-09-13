<div>
    @if($submitted)
        <div class="rounded-sm border border-sand/30 bg-sand/10 px-5 py-4 text-sm">
            Thank you — we have received your enquiry and will be in touch shortly.
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            @if($journey)
                <p class="text-sm text-forest">This enquiry is about <strong>{{ $journey->name }}</strong>.</p>
            @endif

            <div>
                <p class="text-xs tracking-[0.14em] uppercase mb-3 opacity-70">Where do you want to go?</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($countries as $country)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $country->name }}" wire:model="preferredDestinations" class="rounded border-sand-deep text-forest focus:ring-forest">
                            {{ $country->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">How many days?</label>
                    <input type="text" wire:model="days" placeholder="e.g. 8–10" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">How many travellers?</label>
                    <input type="text" wire:model="travellers" placeholder="e.g. 2" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                </div>
            </div>

            <div>
                <p class="text-xs tracking-[0.14em] uppercase mb-3 opacity-70">What experiences interest you?</p>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($experiences as $experience)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $experience->name }}" wire:model="preferredExperiences" class="rounded border-sand-deep text-forest focus:ring-forest">
                            {{ $experience->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Accommodation preference</label>
                    <select wire:model="accommodation" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                        <option value="">Select</option>
                        <option value="Comfortable">Comfortable / mid-range</option>
                        <option value="Luxury">Luxury</option>
                        <option value="Ultra-luxury">Ultra-luxury</option>
                        <option value="Mix">A considered mix</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Approximate investment</label>
                    <select wire:model="investment" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                        <option value="">Select</option>
                        <option value="Mid-range">Mid-range quality</option>
                        <option value="Luxury">Luxury</option>
                        <option value="Ultra-luxury">Ultra-luxury / private proposal</option>
                        <option value="Unsure">Not sure yet</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Travel dates</label>
                <input type="text" wire:model="travelDates" placeholder="e.g. July 2027, flexible" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
            </div>

            <div>
                <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Additional preferences</label>
                <textarea rows="3" wire:model="preferences" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"></textarea>
            </div>

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
                </div>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">WhatsApp</label>
                <input type="text" wire:model="whatsapp" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label for="enquiry-message" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Tell us more</label>
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
