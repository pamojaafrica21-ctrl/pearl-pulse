<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <input type="text" wire:model.live="title" placeholder="Title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <input type="text" wire:model="slug" placeholder="Slug" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <select wire:model="type" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="guide">Guide</option>
            <option value="practical">Practical</option>
            <option value="field">From the Field</option>
        </select>
        <textarea rows="2" wire:model="excerpt" placeholder="Excerpt" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <textarea rows="10" wire:model="body" placeholder="Body (HTML welcome)" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <input type="file" wire:model="cover" accept="image/*">
        <label class="text-sm"><input type="checkbox" wire:model="is_featured" class="rounded text-forest"> Featured</label>
        <button type="submit" class="btn-primary text-xs">Save article</button>
    </form>
</div>
