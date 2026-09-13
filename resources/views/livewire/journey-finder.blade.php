<div>
    <section class="bg-cream pt-16 pb-10 border-b border-sand-deep/20">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <p class="text-xs tracking-[0.22em] uppercase text-gold mb-3">Journey Finder</p>
            <h1 class="font-display text-4xl md:text-6xl text-forest">Not sure where to begin?</h1>
            <p class="mt-4 max-w-2xl text-muted leading-relaxed">Find the Pearl Pulse journey that fits the way you want to experience Africa. Refine — this is not a quiz.</p>
        </div>
    </section>

    <section class="bg-cream py-10">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Country</label>
                    <select wire:model.live="country" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <option value="">Any country</option>
                        @foreach($countries as $countryOption)
                            <option value="{{ $countryOption->slug }}">{{ $countryOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Experience</label>
                    <select wire:model.live="experience" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <option value="">Any experience</option>
                        @foreach($experiences as $experienceOption)
                            <option value="{{ $experienceOption->slug }}">{{ $experienceOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Duration</label>
                    <select wire:model.live="duration" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <option value="">Any length</option>
                        <option value="short">1–5 days</option>
                        <option value="medium">6–9 days</option>
                        <option value="long">10+ days</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Stay style</label>
                    <select wire:model.live="stayStyle" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <option value="">Any style</option>
                        <option value="Comfortable">Comfortable</option>
                        <option value="Luxury">Luxury</option>
                        <option value="Ultra-luxury">Ultra-luxury</option>
                    </select>
                </div>
            </div>
            @if(filled($country) || filled($experience) || filled($duration) || filled($stayStyle))
                <button type="button" wire:click="clearFilters" class="mt-4 text-sm text-forest hover:underline">Clear filters</button>
            @endif
        </div>
    </section>

    <section class="bg-cream pb-20">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
            <p class="text-sm text-muted mb-8">{{ $journeys->count() }} {{ Str::plural('journey', $journeys->count()) }}</p>
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($journeys as $journey)
                    <x-journey-card :journey="$journey" />
                @empty
                    <p class="text-muted col-span-full">No journeys match these filters. Adjust the refinement or <a href="{{ route('plan') }}" class="text-forest underline">tell us what you have in mind</a>.</p>
                @endforelse
            </div>
        </div>
    </section>

    <x-page-cta heading="Still planning?" text="Ask us about your journey — we will shape something around you." button="Ask us about your journey" />
</div>
