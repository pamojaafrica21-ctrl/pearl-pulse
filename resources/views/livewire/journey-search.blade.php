<div
    x-data
    @open-journey-search.window="$wire.openSearch()"
    @keydown.escape.window="if ($wire.open) $wire.closeSearch()"
>
    @if($open)
        <div class="fixed inset-0 z-[80]" role="dialog" aria-modal="true" aria-label="Find your journey">
            <div class="absolute inset-0 bg-charcoal/50 backdrop-blur-sm" wire:click="closeSearch"></div>

            <div class="relative mx-auto mt-[8vh] w-full max-w-2xl px-5">
                <div class="overflow-hidden border border-white/10 bg-white shadow-2xl">
                    <div class="flex items-center gap-3 border-b border-charcoal/10 px-5 py-4">
                        <svg class="h-5 w-5 text-muted shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m21 21-4.3-4.3M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14Z"/>
                        </svg>
                        <input
                            type="search"
                            wire:model.live.debounce.250ms="query"
                            placeholder="Search journeys, destinations, experiences…"
                            class="w-full border-0 bg-transparent p-0 text-base text-charcoal placeholder:text-muted/70 focus:ring-0"
                            autofocus
                        >
                        <button type="button" wire:click="closeSearch" class="text-xs tracking-[0.14em] uppercase text-muted hover:text-forest">Close</button>
                    </div>

                    <div class="max-h-[55vh] overflow-y-auto px-5 py-5">
                        @if(! $hasQuery)
                            <p class="text-sm text-muted leading-relaxed">Type at least two characters, or open the full Journey Finder for filters.</p>
                            <a href="{{ route('journeys.finder') }}" class="mt-5 inline-flex text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70" wire:click="closeSearch">
                                Open Journey Finder
                            </a>
                        @else
                            @if($journeys->isEmpty() && $countries->isEmpty() && $experiences->isEmpty())
                                <p class="text-sm text-muted">No matches. Try another term or browse the finder.</p>
                            @endif

                            @if($journeys->isNotEmpty())
                                <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-3">Journeys</p>
                                <ul class="space-y-1 mb-8">
                                    @foreach($journeys as $journey)
                                        <li>
                                            <a href="{{ route('journeys.show', $journey) }}" class="block px-2 py-2.5 -mx-2 hover:bg-cream transition" wire:click="closeSearch">
                                                <span class="font-display text-xl text-forest">{{ $journey->name }}</span>
                                                @if($journey->countries->isNotEmpty())
                                                    <span class="block text-xs text-muted mt-0.5">{{ $journey->countries->pluck('name')->join(' · ') }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($countries->isNotEmpty())
                                <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-3">Destinations</p>
                                <ul class="space-y-1 mb-8">
                                    @foreach($countries as $country)
                                        <li>
                                            <a href="{{ route('destinations.country', $country) }}" class="block px-2 py-2.5 -mx-2 hover:bg-cream transition" wire:click="closeSearch">
                                                <span class="font-display text-xl text-forest">{{ $country->name }}</span>
                                                @if($country->subtitle)
                                                    <span class="block text-xs text-muted mt-0.5">{{ $country->subtitle }}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($experiences->isNotEmpty())
                                <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-3">Experiences</p>
                                <ul class="space-y-1">
                                    @foreach($experiences as $experience)
                                        <li>
                                            <a href="{{ route('experiences.show', $experience) }}" class="block px-2 py-2.5 -mx-2 hover:bg-cream transition" wire:click="closeSearch">
                                                <span class="font-display text-xl text-forest">{{ $experience->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <div class="mt-8 pt-5 border-t border-charcoal/10">
                                <a href="{{ route('journeys.finder') }}" class="text-sm tracking-[0.14em] uppercase text-forest hover:opacity-70" wire:click="closeSearch">
                                    Advanced Journey Finder
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
