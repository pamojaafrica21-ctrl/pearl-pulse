<div>
    <form wire:submit="save" class="space-y-6 max-w-3xl">
        <div class="grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
                <input type="text" wire:model.live="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Slug</label>
                <input type="text" wire:model="slug" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Best time</label>
                <input type="text" wire:model="best_time" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Subtitle</label>
                <input type="text" wire:model="subtitle" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Teaser</label>
                <textarea rows="2" wire:model="teaser" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Why this country</label>
                <textarea rows="5" wire:model="description" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Practical</label>
                <textarea rows="4" wire:model="practical" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Cover</label>
                @if($country?->cover_path)
                    <img src="{{ $uploader->thumbUrl($country->cover_path) }}" alt="" class="h-20 mb-2">
                @endif
                <input type="file" wire:model="cover" accept="image/*">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Status</label>
                <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Meta title</label>
                <input type="text" wire:model="meta_title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Sort</label>
                <input type="number" wire:model="sort_order" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Meta description</label>
                <textarea rows="2" wire:model="meta_description" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </div>
        <button type="submit" class="btn-primary text-xs">Save country</button>
    </form>
</div>
