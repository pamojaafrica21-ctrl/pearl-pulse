<div>
    <form wire:submit="save" class="space-y-8 max-w-4xl">
        <div class="grid gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Name</label>
                <input type="text" wire:model.live="name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Subtitle</label>
                <input type="text" wire:model="subtitle" placeholder="Short line under the name" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('subtitle') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Slug</label>
                <input type="text" wire:model.blur="slug" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('slug') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Country</label>
                <select wire:model="country_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="">Select</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Region</label>
                <input type="text" wire:model="region" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Sort order</label>
                <input type="number" min="0" wire:model="sort_order" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Teaser</label>
                <textarea rows="2" wire:model="teaser" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Duration</label>
                <input type="text" wire:model="duration" placeholder="e.g. 2–3 nights" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Best time to visit</label>
                <input type="text" wire:model="best_time" placeholder="e.g. June–August" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Activities</label>
                <input type="text" wire:model="activities" placeholder="e.g. Gorilla trekking, nature walks" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">From price</label>
                <input type="text" wire:model="price_from" placeholder="e.g. From $2,400 pp" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
        </div>

        <div>
            <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Full description</label>
            <div wire:ignore>
                <input id="description" type="hidden" value="{{ $description }}">
                <trix-editor input="description" class="trix-content"></trix-editor>
            </div>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-3">
                <label class="text-xs tracking-[0.14em] uppercase text-muted">Highlights</label>
                <button type="button" wire:click="addHighlight" class="text-sm text-forest hover:underline">Add row</button>
            </div>
            <div class="space-y-3">
                @foreach($highlights as $index => $row)
                    <div class="grid gap-3 sm:grid-cols-5" wire:key="hl-{{ $index }}">
                        <input type="text" wire:model="highlights.{{ $index }}.label" placeholder="Label" class="sm:col-span-2 border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <input type="text" wire:model="highlights.{{ $index }}.value" placeholder="Value" class="sm:col-span-2 border-sand-deep/40 focus:border-forest focus:ring-forest">
                        <button type="button" wire:click="removeHighlight({{ $index }})" class="text-sm text-red-700">Remove</button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Cover image</label>
                @if($destination?->cover_path)
                    <div class="mb-3 flex items-start gap-3">
                        <img src="{{ $uploader->thumbUrl($destination->cover_path) }}" alt="" class="h-24 w-36 object-cover">
                        <button type="button" wire:click="removeCover" class="text-sm text-red-700">Remove</button>
                    </div>
                @endif
                <input type="file" wire:model="cover" accept="image/*" class="text-sm">
                <div wire:loading wire:target="cover" class="text-xs text-muted mt-1">Uploading…</div>
                @error('cover') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Gallery images</label>
                <input type="file" wire:model="gallery" accept="image/*" multiple class="text-sm">
                <div wire:loading wire:target="gallery" class="text-xs text-muted mt-1">Uploading…</div>
                @error('gallery.*') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @if($destination?->images?->isNotEmpty())
                    <div class="mt-4 grid grid-cols-3 gap-3">
                        @foreach($destination->images as $image)
                            <div class="relative" wire:key="img-{{ $image->id }}">
                                <img src="{{ $uploader->thumbUrl($image->path) }}" alt="" class="aspect-square object-cover w-full">
                                <button type="button" wire:click="removeGalleryImage({{ $image->id }})" class="absolute top-1 right-1 bg-white/90 text-xs px-1.5 py-0.5 text-red-700">×</button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Meta title</label>
                <input type="text" wire:model="meta_title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Status</label>
                <select wire:model="status" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Meta description</label>
                <textarea rows="2" wire:model="meta_description" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="is_featured" class="rounded border-sand-deep text-forest focus:ring-forest">
                    Featured on homepage
                </label>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary text-xs" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">Save destination</span>
                <span wire:loading wire:target="save">Saving…</span>
            </button>
            <a href="{{ route('admin.destinations.index') }}" wire:navigate class="text-sm text-muted hover:text-forest">Cancel</a>
        </div>
    </form>
</div>

@script
<script>
    const input = document.getElementById('description');
    if (input) {
        input.addEventListener('trix-change', (e) => {
            $wire.set('description', e.target.value);
        });
    }
</script>
@endscript
