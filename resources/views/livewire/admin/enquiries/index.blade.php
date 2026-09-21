<div>
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-2">
            @foreach(['' => 'All', 'new' => 'New', 'in_progress' => 'In progress', 'proposal_sent' => 'Proposal', 'won' => 'Won', 'lost' => 'Lost'] as $value => $label)
                <button
                    type="button"
                    wire:click="$set('status', '{{ $value }}')"
                    class="px-3 py-1.5 text-xs tracking-[0.12em] uppercase border {{ $status === $value ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest' }}"
                >{{ $label }}</button>
            @endforeach
        </div>
        <div class="flex flex-wrap gap-2 shrink-0">
            <a href="{{ route('admin.enquiries.create') }}" wire:navigate class="btn-outline-dark text-xs">Log enquiry</a>
            <a href="{{ route('admin.bookings.create') }}" wire:navigate class="btn-primary text-xs">Log booking</a>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        <select wire:model.live="channel" class="text-sm border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="">All channels</option>
            <option value="website">Website</option>
            <option value="journey_finder">Journey Finder</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="phone">Phone</option>
            <option value="walk_in">Walk-in</option>
            <option value="agent">Agent</option>
        </select>
        <select wire:model.live="assigned" class="text-sm border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="">Anyone</option>
            <option value="mine">Assigned to me</option>
            <option value="unassigned">Unassigned</option>
        </select>
    </div>

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="lg:col-span-3 bg-white border border-sand-deep/30 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                    <tr>
                        <th class="px-4 py-3">From</th>
                        <th class="px-4 py-3">Channel</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Planner</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sand-deep/20">
                    @forelse($enquiries as $enquiry)
                        <tr
                            wire:key="enq-{{ $enquiry->id }}"
                            wire:click="view({{ $enquiry->id }})"
                            class="cursor-pointer hover:bg-cream/80 {{ $viewing === $enquiry->id ? 'bg-cream' : '' }} {{ $enquiry->status === 'new' ? 'font-medium' : '' }}"
                        >
                            <td class="px-4 py-3">
                                <div>{{ $enquiry->name }}</div>
                                <div class="text-xs text-muted font-normal">{{ $enquiry->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block text-[10px] tracking-[0.12em] uppercase px-2 py-0.5 bg-forest/10 text-forest">{{ $enquiry->channelLabel() }}</span>
                            </td>
                            <td class="px-4 py-3">{{ $enquiry->statusLabel() }}</td>
                            <td class="px-4 py-3 text-muted">{{ $enquiry->assignee?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-6 text-muted">No enquiries.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $enquiries->links() }}</div>
        </div>

        <div class="lg:col-span-2 bg-white border border-sand-deep/30 p-5">
            @if($viewingEnquiry)
                <h3 class="font-display text-2xl text-forest">{{ $viewingEnquiry->name }}</h3>
                <p class="text-sm text-muted mt-1">
                    <a href="mailto:{{ $viewingEnquiry->email }}" class="hover:underline">{{ $viewingEnquiry->email }}</a>
                    @if($viewingEnquiry->phone)
                        · {{ $viewingEnquiry->phone }}
                    @endif
                </p>
                <p class="text-xs tracking-[0.12em] uppercase text-gold mt-4">{{ $viewingEnquiry->channelLabel() }} · {{ $viewingEnquiry->statusLabel() }}</p>
                @if($viewingEnquiry->user)
                    <p class="mt-2 text-sm">Account: {{ $viewingEnquiry->user->name }} ({{ $viewingEnquiry->user->email }})</p>
                @endif
                @if($viewingEnquiry->whatsapp)
                    <p class="mt-2 text-sm">WhatsApp: {{ $viewingEnquiry->whatsapp }}</p>
                @endif
                @if($viewingEnquiry->preferred_destinations)
                    <p class="mt-2 text-sm">Countries: {{ implode(', ', $viewingEnquiry->preferred_destinations) }}</p>
                @endif
                @if($viewingEnquiry->days)
                    <p class="mt-2 text-sm">Days: {{ $viewingEnquiry->days }} · Travellers: {{ $viewingEnquiry->travellers }}</p>
                @endif
                @if($viewingEnquiry->preferred_experiences)
                    <p class="mt-2 text-sm">Experiences: {{ implode(', ', $viewingEnquiry->preferred_experiences) }}</p>
                @endif
                @if($viewingEnquiry->accommodation)
                    <p class="mt-2 text-sm">Stay: {{ $viewingEnquiry->accommodation }}@if($viewingEnquiry->investment) · {{ $viewingEnquiry->investment }}@endif</p>
                @endif
                @if($viewingEnquiry->travel_dates)
                    <p class="mt-2 text-sm">Dates: {{ $viewingEnquiry->travel_dates }}</p>
                @endif
                @if($viewingEnquiry->preferences)
                    <div class="mt-2 text-sm whitespace-pre-line">
                        <span class="text-muted">Summary:</span>
                        {{ strip_tags($viewingEnquiry->preferences) }}
                    </div>
                @endif
                <div class="mt-4 text-sm leading-relaxed prose prose-sm max-w-none prose-p:my-2">
                    {!! $viewingEnquiry->message !!}
                </div>

                <div class="mt-6">
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Planner</label>
                    <select
                        wire:change="assign({{ $viewingEnquiry->id }}, $event.target.value)"
                        class="w-full text-sm border-sand-deep/40 focus:border-forest focus:ring-forest"
                    >
                        <option value="">Unassigned</option>
                        @foreach($admins as $admin)
                            <option value="{{ $admin->id }}" @selected($viewingEnquiry->assigned_user_id === $admin->id)>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6 flex flex-wrap gap-2">
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'in_progress')" class="px-3 py-1.5 text-xs border border-sand-deep">In progress</button>
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'proposal_sent')" class="px-3 py-1.5 text-xs border border-sand-deep">Proposal sent</button>
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'lost')" class="px-3 py-1.5 text-xs border border-sand-deep">Lost</button>
                    @if($viewingEnquiry->booking)
                        <a href="{{ route('admin.bookings.edit', $viewingEnquiry->booking) }}" wire:navigate class="px-3 py-1.5 text-xs bg-forest text-sand">Booking {{ $viewingEnquiry->booking->reference }}</a>
                    @else
                        <button type="button" wire:click="convert({{ $viewingEnquiry->id }})" class="px-3 py-1.5 text-xs bg-forest text-sand">Convert to booking</button>
                    @endif
                </div>

                <div class="mt-8 border-t border-sand-deep/30 pt-5">
                    <p class="text-xs tracking-[0.14em] uppercase text-muted mb-3">Notes</p>
                    <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                        @forelse($viewingEnquiry->notes as $note)
                            <div class="text-sm">
                                <p class="text-charcoal whitespace-pre-line">{{ $note->body }}</p>
                                <p class="text-[11px] text-muted mt-1">{{ $note->user?->name ?? 'Admin' }} · {{ $note->created_at->format('d M Y H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-muted">No notes yet.</p>
                        @endforelse
                    </div>
                    <form wire:submit="addNote({{ $viewingEnquiry->id }})" class="space-y-2">
                        <textarea wire:model="note" rows="3" class="w-full text-sm border-sand-deep/40 focus:border-forest focus:ring-forest" placeholder="Add a note…"></textarea>
                        @error('note') <p class="text-sm text-red-700">{{ $message }}</p> @enderror
                        <button type="submit" class="btn-primary text-xs">Add note</button>
                    </form>
                </div>
            @else
                <p class="text-muted text-sm">Select an enquiry to read it, or log a WhatsApp or phone lead.</p>
            @endif
        </div>
    </div>
</div>
