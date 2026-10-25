<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <input
                type="search"
                wire:model.live.debounce.300ms="search"
                placeholder="Search email…"
                class="text-sm border-sand-deep/40 focus:border-forest focus:ring-forest w-64 max-w-full"
            >
            <p class="text-sm text-muted">{{ $total }} {{ $total === 1 ? 'subscriber' : 'subscribers' }}</p>
        </div>
        <button type="button" wire:click="export" class="btn-outline-dark text-xs shrink-0">
            Export CSV
        </button>
    </div>

    <div class="bg-cream border border-sand-deep/30 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Subscribed</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @forelse($subscribers as $subscriber)
                    <tr wire:key="sub-{{ $subscriber->id }}">
                        <td class="px-4 py-3">
                            <a href="mailto:{{ $subscriber->email }}" class="text-forest hover:underline">{{ $subscriber->email }}</a>
                        </td>
                        <td class="px-4 py-3 text-muted whitespace-nowrap">
                            {{ $subscriber->subscribed_at?->format('d M Y H:i') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" wire:click="confirmDelete({{ $subscriber->id }})" class="text-red-700 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6 text-muted">No subscribers yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $subscribers->links() }}</div>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-charcoal/40 px-4">
            <div class="w-full max-w-md bg-cream border border-sand-deep/30 p-6 shadow-lg">
                <h3 class="font-display text-2xl text-forest">Remove subscriber?</h3>
                <p class="mt-2 text-sm text-muted">This email will be deleted from the list.</p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cancelDelete" class="btn-outline-dark text-xs">Cancel</button>
                    <button type="button" wire:click="delete" class="btn-primary text-xs bg-red-800 border-red-800 hover:bg-red-900">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
