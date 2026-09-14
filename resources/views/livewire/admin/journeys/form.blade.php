<div>
    <form wire:submit="save" class="space-y-8 max-w-4xl">
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
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Days</label>
                <input type="number" wire:model="days" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Teaser</label>
                <textarea rows="2" wire:model="teaser" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Overview</label>
                <textarea rows="5" wire:model="overview" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Price mode</label>
                <select wire:model="price_mode" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="from">From $X</option>
                    <option value="tailored">Tailored to your journey</option>
                    <option value="proposal">Request a private proposal</option>
                </select>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">From price</label>
                <input type="text" wire:model="price_from" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Included (one per line)</label>
                <textarea rows="4" wire:model="includedLines" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Not included (one per line)</label>
                <textarea rows="4" wire:model="notIncludedLines" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
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
        <div>
            @include('livewire.admin.partials.video-field', ['record' => $journey ?? null, 'uploader' => $videoUploader])
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <p class="text-xs tracking-[0.14em] uppercase text-muted mb-2">Countries</p>
                @foreach($countries as $country)
                    <label class="flex items-center gap-2 text-sm mb-1"><input type="checkbox" value="{{ $country->id }}" wire:model="countryIds" class="rounded text-forest"> {{ $country->name }}</label>
                @endforeach
            </div>
            <div>
                <p class="text-xs tracking-[0.14em] uppercase text-muted mb-2">Experiences</p>
                @foreach($experiences as $experience)
                    <label class="flex items-center gap-2 text-sm mb-1"><input type="checkbox" value="{{ $experience->id }}" wire:model="experienceIds" class="rounded text-forest"> {{ $experience->name }}</label>
                @endforeach
            </div>
            <div>
                <p class="text-xs tracking-[0.14em] uppercase text-muted mb-2">Destinations</p>
                @foreach($destinations as $destination)
                    <label class="flex items-center gap-2 text-sm mb-1"><input type="checkbox" value="{{ $destination->id }}" wire:model="destinationIds" class="rounded text-forest"> {{ $destination->name }}</label>
                @endforeach
            </div>
            <div>
                <p class="text-xs tracking-[0.14em] uppercase text-muted mb-2">Selected stays</p>
                @foreach($stays as $stay)
                    <label class="flex items-center gap-2 text-sm mb-1"><input type="checkbox" value="{{ $stay->id }}" wire:model="stayIds" class="rounded text-forest"> {{ $stay->name }}</label>
                @endforeach
            </div>
        </div>
        <div class="flex gap-4">
            <label class="text-sm"><input type="checkbox" wire:model="is_signature" class="rounded text-forest"> Signature</label>
            <label class="text-sm"><input type="checkbox" wire:model="is_multi_country" class="rounded text-forest"> Multi-country</label>
            <label class="text-sm"><input type="checkbox" wire:model="is_featured" class="rounded text-forest"> Featured</label>
        </div>
        <button type="submit" class="btn-primary text-xs">Save journey</button>
    </form>
</div>
