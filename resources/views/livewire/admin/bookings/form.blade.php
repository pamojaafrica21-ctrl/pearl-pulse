@php
    $statusOptions = [
        'held' => 'Held',
        'confirmed' => 'Confirmed',
        'in_travel' => 'Travelling',
        'completed' => 'Travelled',
        'cancelled' => 'Cancelled',
    ];
    $field = 'w-full border-sand-deep/40 focus:border-forest focus:ring-forest text-sm';
@endphp

<div>
    @if($booking?->exists)
        <div class="mb-8">
            <p class="text-[11px] tracking-[0.16em] uppercase text-muted">
                {{ $booking->reference }}
                · {{ $booking->channelLabel() }}
                @if($booking->enquiry)
                    · <a href="{{ route('admin.enquiries.index', ['open' => $booking->enquiry_id]) }}" wire:navigate class="text-forest hover:opacity-70">Linked enquiry</a>
                @endif
            </p>
            <p class="mt-2 text-sm text-muted">{{ $booking->timingLabel() }}@if($booking->datesLabel()) · {{ $booking->datesLabel() }}@endif</p>

            <div class="flex flex-wrap gap-2 mt-5">
                @foreach($statusOptions as $value => $label)
                    <button
                        type="button"
                        wire:click="setStatus('{{ $value }}')"
                        class="px-3 py-1.5 text-[11px] tracking-[0.12em] uppercase border transition {{ $status === $value ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest hover:border-forest' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid gap-8 {{ $booking?->exists ? 'xl:grid-cols-[minmax(0,1fr)_22rem] xl:items-start' : '' }}">
        <form wire:submit="save" class="space-y-8">
            <section class="bg-white border border-sand-deep/30 p-5 sm:p-6">
                <p class="text-[11px] tracking-[0.16em] uppercase text-muted mb-4">Guest</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
                        <input type="text" wire:model="name" class="{{ $field }}">
                        @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
                        <input type="email" wire:model="email" class="{{ $field }}">
                        @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Phone</label>
                        <input type="text" wire:model="phone" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">WhatsApp</label>
                        <input type="text" wire:model="whatsapp" class="{{ $field }}">
                    </div>
                </div>
                @if($booking?->exists && ($booking->email || $booking->phone || $booking->whatsapp))
                    <div class="mt-4 flex flex-wrap gap-3 text-xs tracking-[0.12em] uppercase">
                        @if($booking->email)
                            <a href="mailto:{{ $booking->email }}" class="text-forest hover:opacity-70">Email</a>
                        @endif
                        @if($booking->phone)
                            <a href="tel:{{ $booking->phone }}" class="text-forest hover:opacity-70">Call</a>
                        @endif
                        @if($booking->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $booking->whatsapp) }}" target="_blank" rel="noopener" class="text-forest hover:opacity-70">WhatsApp</a>
                        @endif
                    </div>
                @endif
            </section>

            <section class="bg-white border border-sand-deep/30 p-5 sm:p-6">
                <p class="text-[11px] tracking-[0.16em] uppercase text-muted mb-4">Journey</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    @if(! $booking?->exists)
                        <div>
                            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Status</label>
                            <select wire:model="status" class="{{ $field }}">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Channel</label>
                        <select wire:model="channel" class="{{ $field }}">
                            <option value="whatsapp">WhatsApp</option>
                            <option value="phone">Phone</option>
                            <option value="walk_in">Walk-in</option>
                            <option value="agent">Agent</option>
                            <option value="website">Website</option>
                            <option value="journey_finder">Journey Finder</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Itinerary</label>
                        <select wire:model="journey_id" class="{{ $field }}">
                            <option value="">Custom itinerary</option>
                            @foreach($journeys as $journey)
                                <option value="{{ $journey->id }}">{{ $journey->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Planner</label>
                        <select wire:model="assigned_user_id" class="{{ $field }}">
                            <option value="">Me</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Travellers</label>
                        <input type="text" wire:model="travellers" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Investment</label>
                        <input type="text" wire:model="investment" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Start date</label>
                        <input type="date" wire:model="start_date" class="{{ $field }}">
                    </div>
                    <div>
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">End date</label>
                        <input type="date" wire:model="end_date" class="{{ $field }}">
                        @error('end_date') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Internal summary</label>
                        <textarea wire:model="summary" rows="4" class="{{ $field }}"></textarea>
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap gap-3">
                <button type="submit" class="btn-primary text-xs">Save booking</button>
                <a href="{{ route('admin.bookings.index') }}" wire:navigate class="text-sm self-center text-muted hover:text-forest">All bookings</a>
            </div>
        </form>

        @if($booking?->exists)
            <aside class="bg-white border border-sand-deep/30 p-5 sm:p-6 xl:sticky xl:top-6">
                <p class="text-[11px] tracking-[0.16em] uppercase text-muted mb-4">Notes</p>
                <form wire:submit="addNote" class="space-y-2 mb-6">
                    <textarea wire:model="note" rows="4" class="{{ $field }}" placeholder="Lodge hold, permit, call…"></textarea>
                    @error('note') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-outline-dark text-xs">Add note</button>
                </form>
                <div class="space-y-4 max-h-[28rem] overflow-y-auto">
                    @forelse($notes as $item)
                        <div class="border-t border-sand-deep/20 pt-4 first:border-0 first:pt-0">
                            <p class="text-sm text-charcoal whitespace-pre-line">{{ $item->body }}</p>
                            <p class="text-[11px] text-muted mt-1">{{ $item->user?->name ?? 'Admin' }} · {{ $item->created_at->format('d M Y H:i') }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-muted">No notes yet.</p>
                    @endforelse
                </div>
            </aside>
        @endif
    </div>
</div>
