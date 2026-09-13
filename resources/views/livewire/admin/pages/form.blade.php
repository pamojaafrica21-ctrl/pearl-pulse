<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <input type="text" wire:model.live="title" placeholder="Title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <input type="text" wire:model="slug" placeholder="Slug" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <textarea rows="10" wire:model="content" placeholder="Content" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <button type="submit" class="btn-primary text-xs">Save page</button>
    </form>
</div>
