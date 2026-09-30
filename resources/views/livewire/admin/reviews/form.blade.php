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
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Guest photo (optional)</label>
            @if($review?->coverUrl())
                <img src="{{ $review->coverThumbUrl() ?: $review->coverUrl() }}" alt="" class="mb-3 h-24 w-24 object-cover">
            @endif
            <input type="file" wire:model="cover" accept="image/*">
        </div>
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Pending / hidden</option>
            <option value="published">Published on website</option>
        </select>
        <p class="text-xs text-muted -mt-3">Guest-submitted reviews arrive as pending until you publish them.</p>
        <button type="submit" class="btn-primary text-xs">Save review</button>
    </form>
</div>
