<div>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex flex-wrap gap-2 text-sm">
            <button type="button" wire:click="$set('filter', 'all')" class="px-3 py-1.5 border {{ $filter === 'all' ? 'border-forest text-forest' : 'border-sand-deep/40 text-muted' }}">All</button>
            <button type="button" wire:click="$set('filter', 'pending')" class="px-3 py-1.5 border {{ $filter === 'pending' ? 'border-forest text-forest' : 'border-sand-deep/40 text-muted' }}">
                Pending
                @if($pendingCount)
                    <span class="ml-1 inline-flex min-w-[1.25rem] justify-center rounded-full bg-gold/20 px-1.5 text-xs text-gold">{{ $pendingCount }}</span>
                @endif
            </button>
            <button type="button" wire:click="$set('filter', 'published')" class="px-3 py-1.5 border {{ $filter === 'published' ? 'border-forest text-forest' : 'border-sand-deep/40 text-muted' }}">Published</button>
        </div>
        <a href="{{ route('admin.reviews.create') }}" wire:navigate class="btn-primary text-xs">Add review</a>
    </div>

    @if(session('status'))
        <p class="mb-4 text-sm text-forest">{{ session('status') }}</p>
    @endif

    <div class="bg-white border border-sand-deep/30 overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">Guest</th>
                    <th class="px-4 py-3">Journey</th>
                    <th class="px-4 py-3">Review</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Submitted</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @forelse($reviews as $review)
                    <tr wire:key="review-{{ $review->id }}">
                        <td class="px-4 py-3 align-top">
                            <div class="font-medium text-forest">{{ $review->guest_name }}</div>
                            @if($review->guest_country)
                                <div class="text-xs text-muted mt-0.5">{{ $review->guest_country }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 align-top text-muted">{{ $review->journey?->name ?: '—' }}</td>
                        <td class="px-4 py-3 align-top max-w-md">
                            <p class="text-forest leading-relaxed">{{ \Illuminate\Support\Str::limit($review->quote, 160) }}</p>
                        </td>
                        <td class="px-4 py-3 align-top">
                            <span class="inline-block px-2 py-0.5 text-xs uppercase tracking-wide {{ $review->status === 'published' ? 'bg-forest/10 text-forest' : 'bg-sand text-muted' }}">
                                {{ $review->status === 'draft' ? 'Pending' : $review->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 align-top text-muted whitespace-nowrap">{{ $review->created_at?->format('d M Y') }}</td>
                        <td class="px-4 py-3 align-top text-right space-x-3 whitespace-nowrap">
                            @if($review->status !== 'published')
                                <button type="button" wire:click="publish({{ $review->id }})" class="text-forest hover:underline">Publish</button>
                            @else
                                <button type="button" wire:click="unpublish({{ $review->id }})" class="text-muted hover:underline">Hide</button>
                            @endif
                            <a href="{{ route('admin.reviews.edit', $review) }}" wire:navigate class="text-forest hover:underline">Edit</a>
                            <button type="button" wire:click="confirmDelete({{ $review->id }})" class="text-red-700 hover:underline">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-muted">No reviews yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4">
            <div class="bg-white p-6 max-w-md w-full border border-sand-deep/30">
                <h3 class="font-display text-2xl text-forest">Delete review?</h3>
                <p class="mt-2 text-sm text-muted">This cannot be undone.</p>
                <div class="mt-6 flex gap-3 justify-end">
                    <button type="button" wire:click="cancelDelete" class="px-4 py-2 text-sm border border-sand-deep">Cancel</button>
                    <button type="button" wire:click="delete" class="px-4 py-2 text-sm bg-red-700 text-white">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
