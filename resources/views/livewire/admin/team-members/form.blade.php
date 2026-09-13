<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
            <input type="text" wire:model.live="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Role</label>
            <input type="text" wire:model="role" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        </div>
        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Bio</label>
            <textarea rows="5" wire:model="bio" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        </div>
        <div class="grid gap-5 md:grid-cols-2">
            <select wire:model="status" class="border-sand-deep/40 focus:border-forest focus:ring-forest">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <input type="file" wire:model="cover" accept="image/*">
        </div>
        <button type="submit" class="btn-primary text-xs">Save</button>
    </form>
</div>
