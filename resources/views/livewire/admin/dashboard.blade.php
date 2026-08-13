<div>
    <div class="grid gap-4 sm:grid-cols-3 mb-10">
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Destinations</p>
            <p class="font-display text-4xl text-forest mt-2">{{ $destinationCount }}</p>
            <p class="text-sm text-muted mt-1">{{ $publishedCount }} published</p>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">New enquiries</p>
            <p class="font-display text-4xl text-forest mt-2">{{ $newEnquiries }}</p>
            <a href="{{ route('admin.enquiries.index') }}" class="text-sm text-gold mt-2 inline-block" wire:navigate>View inbox →</a>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Quick actions</p>
            <a href="{{ route('admin.destinations.create') }}" class="btn-primary mt-4 text-xs" wire:navigate>New destination</a>
        </div>
    </div>

    <h2 class="font-display text-2xl text-forest mb-4">Recent enquiries</h2>
    <div class="bg-white border border-sand-deep/30 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Destination</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @forelse($recentEnquiries as $enquiry)
                    <tr>
                        <td class="px-4 py-3">{{ $enquiry->name }}</td>
                        <td class="px-4 py-3">{{ $enquiry->destination?->name ?? 'General' }}</td>
                        <td class="px-4 py-3 capitalize">{{ $enquiry->status }}</td>
                        <td class="px-4 py-3 text-muted">{{ $enquiry->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-muted">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
