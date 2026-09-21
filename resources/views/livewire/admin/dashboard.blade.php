<div wire:poll.5s>
    {{-- Site visibility --}}
    <div class="mb-10 bg-white border border-sand-deep/30 p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Public site</p>
            <p class="font-display text-2xl text-forest mt-1">
                {{ $sitePublic ? 'Live' : 'Hidden' }}
            </p>
            <p class="text-sm text-muted mt-1">
                @if($sitePublic)
                    Visitors can browse the website. Admin remains accessible.
                @else
                    Visitors see a coming-soon page. Login and admin still work.
                @endif
            </p>
        </div>
        <button
            type="button"
            wire:click="toggleSiteVisibility"
            class="shrink-0 px-6 py-3 text-xs tracking-[0.14em] uppercase transition {{ $sitePublic ? 'bg-red-800 text-white hover:bg-red-900' : 'btn-primary' }}"
        >
            {{ $sitePublic ? 'Hide public site' : 'Make site live' }}
        </button>
    </div>

    @if(! $sitePublic)
        <div class="mb-10 bg-white border border-sand-deep/30 p-6">
            <p class="text-xs tracking-[0.15em] uppercase text-muted mb-2">Maintenance message</p>
            <form wire:submit="saveMaintenanceMessage" class="flex flex-col sm:flex-row gap-3">
                <input
                    type="text"
                    wire:model="maintenanceMessage"
                    placeholder="We are preparing something special…"
                    class="flex-1 border-sand-deep/40 focus:border-forest focus:ring-forest text-sm"
                >
                <button type="submit" class="btn-primary text-xs shrink-0">Save message</button>
            </form>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Total page views</p>
            <p class="font-display text-4xl text-forest mt-2">{{ number_format($visitStats['total_views']) }}</p>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Today</p>
            <p class="font-display text-4xl text-forest mt-2">{{ number_format($visitStats['today_views']) }}</p>
            <p class="text-sm text-muted mt-1">{{ number_format($visitStats['today_unique']) }} unique visitors</p>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Last 7 days</p>
            <p class="font-display text-4xl text-forest mt-2">{{ number_format($visitStats['week_views']) }}</p>
            <p class="text-sm text-muted mt-1">{{ number_format($visitStats['week_unique']) }} unique visitors</p>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Last 30 days</p>
            <p class="font-display text-4xl text-forest mt-2">{{ number_format($visitStats['month_views']) }}</p>
            <p class="text-sm text-muted mt-1">{{ number_format($visitStats['month_unique']) }} unique visitors</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-10">
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Destinations</p>
            <p class="font-display text-4xl text-forest mt-2">{{ $destinationCount }}</p>
            <p class="text-sm text-muted mt-1">{{ $publishedCount }} published</p>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Open enquiries</p>
            <p class="font-display text-4xl text-forest mt-2">{{ $openEnquiries }}</p>
            <p class="text-sm text-muted mt-1">{{ $newEnquiries }} new</p>
            <a href="{{ route('admin.enquiries.index') }}" class="text-sm text-gold mt-2 inline-block" wire:navigate>View inbox →</a>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Confirmed bookings</p>
            <p class="font-display text-4xl text-forest mt-2">{{ $confirmedBookings }}</p>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gold mt-2 inline-block" wire:navigate>View bookings →</a>
        </div>
        <div class="bg-white border border-sand-deep/30 p-5">
            <p class="text-xs tracking-[0.15em] uppercase text-muted">Quick actions</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('admin.enquiries.create') }}" class="btn-primary text-xs inline-block" wire:navigate>Log enquiry</a>
                <a href="{{ route('admin.bookings.create') }}" class="btn-outline-dark text-xs inline-block" wire:navigate>Log booking</a>
            </div>
        </div>
    </div>

    <h2 class="font-display text-2xl text-forest mb-4">Recent enquiries</h2>
    <div class="bg-white border border-sand-deep/30 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Channel</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-sand-deep/20">
                @forelse($recentEnquiries as $enquiry)
                    <tr>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.enquiries.index', ['open' => $enquiry->id]) }}" wire:navigate class="hover:text-forest">{{ $enquiry->name }}</a>
                        </td>
                        <td class="px-4 py-3">{{ $enquiry->channelLabel() }}</td>
                        <td class="px-4 py-3">{{ $enquiry->statusLabel() }}</td>
                        <td class="px-4 py-3 text-muted">{{ $enquiry->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-muted">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
