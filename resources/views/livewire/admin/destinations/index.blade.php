<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex gap-3 flex-1">
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search…" class="w-full max-w-xs border-sand-deep/40 text-sm focus:border-forest focus:ring-forest">
            <select wire:model.live="sort" class="border-sand-deep/40 text-sm focus:border-forest focus:ring-forest">
                <option value="name">Sort: Name</option>
                <option value="country">Sort: Country</option>
                <option value="status">Sort: Status</option>
                <option value="updated_at">Sort: Updated</option>
            </select>
        </div>
        <a href="{{ route('admin.destinations.create') }}" wire:navigate class="btn-primary text-xs">Add destination</a>
    </div>

    <div class="bg-white border border-sand-deep/30 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">Cover</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Country</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @foreach($destinations as $destination)
                    <tr wire:key="dest-{{ $destination->id }}">
                        <td class="px-4 py-3">
                            @if($destination->cover_path)
                                <img src="{{ $uploader->thumbUrl($destination->cover_path) }}" alt="" class="h-12 w-16 object-cover">
                            @else
                                <div class="h-12 w-16 bg-forest/10"></div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-forest">{{ $destination->name }}</div>
                            <div class="text-xs text-muted">{{ $destination->slug }}</div>
                        </td>
                        <td class="px-4 py-3">{{ $destination->country?->name }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 text-xs uppercase tracking-wide {{ $destination->status === 'published' ? 'bg-forest/10 text-forest' : 'bg-sand text-muted' }}">
                                {{ $destination->status }}
                            </span>
                            @if($destination->is_featured)
                                <span class="ml-1 text-xs text-gold">Featured</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            <a href="{{ route('admin.destinations.edit', $destination) }}" wire:navigate class="text-forest hover:underline">Edit</a>
                            <button type="button" wire:click="confirmDelete({{ $destination->id }})" class="text-red-700 hover:underline">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $destinations->links() }}</div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="bg-white p-6 max-w-md w-full border border-sand-deep/30">
                <h3 class="font-display text-2xl text-forest">Delete destination?</h3>
                <p class="mt-2 text-sm text-muted">This cannot be undone. Cover and gallery images will be removed.</p>
                <div class="mt-6 flex gap-3 justify-end">
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2 text-sm border border-sand-deep">Cancel</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 text-sm bg-red-700 text-white">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
