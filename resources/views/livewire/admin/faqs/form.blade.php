<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <input type="text" wire:model="question" placeholder="Question" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <textarea rows="4" wire:model="answer" placeholder="Answer" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <select wire:model="group" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="site">Site</option>
            <option value="country">Country</option>
            <option value="journey">Journey</option>
            <option value="experience">Experience</option>
        </select>
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <button type="submit" class="btn-primary text-xs">Save FAQ</button>
    </form>
</div>
