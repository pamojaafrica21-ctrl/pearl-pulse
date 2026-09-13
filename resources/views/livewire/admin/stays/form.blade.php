<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <p class="text-sm text-muted">Never describe these as “our lodges”. They are selected stays.</p>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
            <input type="text" wire:model.live="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Slug</label>
            <input type="text" wire:model="slug" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Teaser</label>
            <textarea rows="2" wire:model="teaser" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Description</label>
            <textarea rows="5" wire:model="description" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Location</label>
            <input type="text" wire:model="location" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Style</label>
            <select wire:model="style" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                <option value="">Select</option>
                <option value="Comfortable">Comfortable</option>
                <option value="Luxury">Luxury</option>
                <option value="Ultra-luxury">Ultra-luxury</option>
            </select>
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Nearby destination</label>
            <select wire:model="destination_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                <option value="">None</option>
                @foreach($destinations as $destination)
                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Status</label>
                <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Cover</label>
                <input type="file" wire:model="cover" accept="image/*">
            </div>
        </div>
        <label class="text-sm"><input type="checkbox" wire:model="is_featured" class="rounded text-forest"> Featured</label>
        <div><button type="submit" class="btn-primary text-xs">Save stay</button></div>
    </form>
</div>
