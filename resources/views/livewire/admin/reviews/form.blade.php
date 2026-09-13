<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <input type="text" wire:model="guest_name" placeholder="Guest name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <input type="text" wire:model="guest_country" placeholder="Guest country" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <textarea rows="4" wire:model="quote" placeholder="Quote" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <select wire:model="journey_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="">Related journey (optional)</option>
            @foreach($journeys as $journey)
                <option value="{{ $journey->id }}">{{ $journey->name }}</option>
            @endforeach
        </select>
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <button type="submit" class="btn-primary text-xs">Save review</button>
    </form>
</div>
