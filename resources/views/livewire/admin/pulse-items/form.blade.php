<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <p class="text-sm text-muted">Items appear on the site only when they are published and approved.</p>
        <input type="text" wire:model="title" placeholder="Title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <select wire:model="type" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="photo">Photo</option>
            <option value="reel">Reel</option>
            <option value="story">Story</option>
        </select>
        <textarea rows="3" wire:model="caption" placeholder="Caption" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <input type="text" wire:model="guest_name" placeholder="Guest name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <input type="url" wire:model="video_url" placeholder="Video URL (optional)" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <label class="text-sm"><input type="checkbox" wire:model="approved" class="rounded text-forest"> Approved for public display</label>
        <input type="file" wire:model="cover" accept="image/*">
        <button type="submit" class="btn-primary text-xs">Save</button>
    </form>
</div>
