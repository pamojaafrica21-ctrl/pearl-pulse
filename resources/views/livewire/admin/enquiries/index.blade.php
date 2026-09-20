<div wire:poll.5s>
    <div class="flex gap-2 mb-6">
        @foreach(['' => 'All', 'new' => 'New', 'read' => 'Read', 'responded' => 'Responded'] as $value => $label)
            <button
                type="button"
                wire:click="$set('status', '{{ $value }}')"
                class="px-3 py-1.5 text-xs tracking-[0.12em] uppercase border {{ $status === $value ? 'bg-forest text-sand border-forest' : 'border-sand-deep text-forest' }}"
            >{{ $label }}</button>
        @endforeach
    </div>

    <div class="grid gap-6 lg:grid-cols-5">
        <div class="lg:col-span-3 bg-white border border-sand-deep/30 overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-cream text-left text-xs tracking-wider uppercase text-muted">
                    <tr>
                        <th class="px-4 py-3">From</th>
                        <th class="px-4 py-3">Source</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Date</th>
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
                                @if($enquiry->source === 'journey_finder')
                                    <span class="inline-block text-[10px] tracking-[0.12em] uppercase px-2 py-0.5 bg-forest/10 text-forest">Journey Finder</span>
                                @else
                                    <span class="text-muted">{{ $enquiry->journey?->name ?? $enquiry->destination?->name ?? 'Plan form' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 capitalize">{{ $enquiry->status }}</td>
                            <td class="px-4 py-3 text-muted">{{ $enquiry->created_at->format('d M Y H:i') }}</td>
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
                @if($viewingEnquiry->source === 'journey_finder')
                    <p class="text-xs tracking-[0.12em] uppercase text-gold mt-4">Journey Finder quiz</p>
                @else
                    <p class="text-xs tracking-[0.12em] uppercase text-gold mt-4">
                        {{ $viewingEnquiry->journey?->name ?? $viewingEnquiry->destination?->name ?? 'General enquiry' }}
                    </p>
                @endif
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
                <div class="mt-6 flex flex-wrap gap-2">
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'read')" class="px-3 py-1.5 text-xs border border-sand-deep">Mark read</button>
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'responded')" class="px-3 py-1.5 text-xs bg-forest text-sand">Mark responded</button>
                    <button type="button" wire:click="mark({{ $viewingEnquiry->id }}, 'new')" class="px-3 py-1.5 text-xs border border-sand-deep">Mark new</button>
                </div>
            @else
                <p class="text-muted text-sm">Select an enquiry to read it.</p>
            @endif
        </div>
    </div>
</div>
