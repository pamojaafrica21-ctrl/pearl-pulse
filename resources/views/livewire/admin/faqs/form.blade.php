<div>
    <form wire:submit="save" class="space-y-5 max-w-3xl">
        <input type="text" wire:model="question" placeholder="Question" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
        <textarea rows="4" wire:model="answer" placeholder="Answer" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
        <select wire:model.live="group" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="site">Site</option>
            <option value="country">Country</option>
            <option value="journey">Journey</option>
            <option value="experience">Experience</option>
        </select>
        @if($group === 'site')
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Topic (site FAQs)</label>
                <select wire:model="topic" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="">General</option>
                    @foreach($topics as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <button type="submit" class="btn-primary text-xs">Save FAQ</button>
    </form>
</div>
