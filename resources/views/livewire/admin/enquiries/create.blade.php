<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
                <input type="text" wire:model="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('name') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Email</label>
                <input type="email" wire:model="email" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('email') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Phone</label>
                <input type="text" wire:model="phone" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">WhatsApp</label>
                <input type="text" wire:model="whatsapp" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Channel</label>
                <select wire:model="channel" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="whatsapp">WhatsApp</option>
                    <option value="phone">Phone</option>
                    <option value="walk_in">Walk-in</option>
                    <option value="agent">Agent</option>
                    <option value="website">Website</option>
                    <option value="journey_finder">Journey Finder</option>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Status</label>
                <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="new">New</option>
                    <option value="in_progress">In progress</option>
                    <option value="proposal_sent">Proposal sent</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Journey</label>
                <select wire:model="journey_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="">None yet</option>
                    @foreach($journeys as $journey)
                        <option value="{{ $journey->id }}">{{ $journey->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Planner</label>
                <select wire:model="assigned_user_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="">Me</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Travellers</label>
                <input type="text" wire:model="travellers" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Days</label>
                <input type="text" wire:model="days" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Travel dates</label>
                <input type="text" wire:model="travel_dates" placeholder="e.g. July 2027 or 2027-07-12 to 2027-07-20" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Investment</label>
                <input type="text" wire:model="investment" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Notes from the conversation</label>
                <textarea wire:model="message" rows="5" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="btn-primary text-xs">Save enquiry</button>
            <a href="{{ route('admin.enquiries.index') }}" wire:navigate class="text-sm self-center text-muted hover:text-forest">Cancel</a>
        </div>
    </form>
</div>
