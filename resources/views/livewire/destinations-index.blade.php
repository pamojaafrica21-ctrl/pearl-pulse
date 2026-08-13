<div>
    <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-10">
        <div class="flex flex-wrap gap-2">
            <button
                type="button"
                wire:click="$set('country', '')"
                class="px-4 py-2 text-xs tracking-[0.14em] uppercase border transition {{ $country === '' ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest hover:border-forest' }}"
            >All</button>
            @foreach($countries as $c)
                <button
                    type="button"
                    wire:click="$set('country', '{{ $c }}')"
                    class="px-4 py-2 text-xs tracking-[0.14em] uppercase border transition {{ $country === $c ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest hover:border-forest' }}"
                >{{ $c }}</button>
            @endforeach
        </div>
        <div class="w-full md:w-72">
            <label for="destination-search" class="sr-only">Search</label>
            <input
                id="destination-search"
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search destinations…"
                class="w-full border-sand-deep/50 bg-white/70 text-sm focus:border-forest focus:ring-forest"
            >
        </div>
    </div>

    <div wire:loading.delay class="text-sm text-muted mb-4">Updating…</div>

    <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($destinations as $destination)
            <x-destination-card :destination="$destination" />
        @empty
            <p class="col-span-full text-muted">No destinations match your filters.</p>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $destinations->links() }}
    </div>
</div>
