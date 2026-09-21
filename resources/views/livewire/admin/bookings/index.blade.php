@php
    $filters = [
        '' => 'All',
        'held' => 'Held',
        'confirmed' => 'Confirmed',
        'in_travel' => 'Travelling',
        'completed' => 'Travelled',
        'cancelled' => 'Cancelled',
    ];
@endphp

<div>
    <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
        <div>
            <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Operations</p>
            <p class="mt-1 text-sm text-muted max-w-xl">Soonest travel first. Open a file to change status, dates, or add a lodge note.</p>
        </div>
        <a href="{{ route('admin.bookings.create') }}" wire:navigate class="btn-primary text-xs shrink-0">Log booking</a>
    </div>

    <div class="flex flex-wrap gap-2 mb-5">
        @foreach($filters as $value => $label)
            @php $count = $value === '' ? $total : (int) ($counts[$value] ?? 0); @endphp
            <button
                type="button"
                wire:click="$set('status', '{{ $value }}')"
                class="inline-flex items-center gap-2 px-3 py-1.5 text-[11px] tracking-[0.12em] uppercase border transition {{ $status === $value ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest hover:border-forest' }}"
            >
                {{ $label }}
                <span class="tabular-nums {{ $status === $value ? 'text-sand/70' : 'text-muted' }}">{{ $count }}</span>
            </button>
        @endforeach
    </div>

    <div class="mb-6">
        <label class="sr-only">Search bookings</label>
        <input
            type="search"
            wire:model.live.debounce.400ms="q"
            placeholder="Search name, email, phone, or PP- reference"
            class="w-full max-w-md border-sand-deep/40 focus:border-forest focus:ring-forest text-sm"
        >
    </div>

    <div class="space-y-3">
        @forelse($bookings as $booking)
            <a
                wire:key="bk-{{ $booking->id }}"
                href="{{ route('admin.bookings.edit', $booking) }}"
                wire:navigate
                class="group flex flex-col sm:flex-row sm:items-center gap-4 bg-white border border-sand-deep/30 px-5 py-4 transition hover:border-forest/40"
            >
                <div class="min-w-0 flex-1">
                    <p class="text-[11px] tracking-[0.14em] uppercase text-muted">
                        {{ $booking->reference }}
                        · {{ $booking->channelLabel() }}
                        @if($booking->notes_count)
                            · {{ $booking->notes_count }} {{ \Illuminate\Support\Str::plural('note', $booking->notes_count) }}
                        @endif
                    </p>
                    <h2 class="font-display text-2xl text-forest mt-1 leading-tight group-hover:opacity-80">{{ $booking->name }}</h2>
                    <p class="mt-1 text-sm text-muted truncate">
                        {{ $booking->journey?->name ?? 'Custom itinerary' }}
                        @if($booking->travellers)
                            · {{ $booking->travellers }} travellers
                        @endif
                        @if($booking->assignee)
                            · {{ $booking->assignee->name }}
                        @endif
                    </p>
                </div>

                <div class="flex items-center justify-between gap-4 sm:flex-col sm:items-end sm:justify-center sm:text-right shrink-0">
                    <div>
                        <p class="font-display text-xl text-charcoal leading-tight">{{ $booking->datesLabel() ?? 'Dates TBC' }}</p>
                        <p class="mt-1 text-xs text-muted">
                            {{ $booking->timingLabel() }}
                            @if($booking->nights())
                                · {{ $booking->nights() }} {{ \Illuminate\Support\Str::plural('night', $booking->nights()) }}
                            @endif
                        </p>
                    </div>
                    <span class="inline-block text-[10px] tracking-[0.14em] uppercase px-2.5 py-1 border {{ $booking->statusTone() }}">
                        {{ $booking->statusLabel() }}
                    </span>
                </div>
            </a>
        @empty
            <div class="bg-white border border-sand-deep/30 px-6 py-12 max-w-xl">
                <p class="text-[11px] tracking-[0.18em] uppercase text-muted">Empty desk</p>
                <p class="font-display text-3xl text-forest mt-2">No bookings here</p>
                <p class="mt-3 text-sm text-muted leading-relaxed">
                    Convert a won enquiry, or log an offline sale from WhatsApp, a walk-in, or an agent.
                </p>
                <a href="{{ route('admin.bookings.create') }}" wire:navigate class="btn-primary text-xs mt-6 inline-flex">Log booking</a>
            </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
        <div class="mt-6">{{ $bookings->links() }}</div>
    @endif
</div>
