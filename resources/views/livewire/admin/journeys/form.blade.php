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
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Subtitle</label>
                <input type="text" wire:model="subtitle" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
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
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Best time</label>
                <input type="text" wire:model="best_time" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Practical (HTML ok)</label>
                <textarea rows="4" wire:model="practical" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Map embed URL</label>
                <input type="url" wire:model="map_embed_url" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
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

        <section class="space-y-4 border-t border-sand-deep/30 pt-8">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 class="font-display text-2xl text-forest">Day by day</h2>
                    <p class="mt-1 text-sm text-muted">Title, description, lodging, meals, and an optional day image.</p>
                </div>
                <button type="button" wire:click="addDay" class="text-sm text-forest hover:underline">Add day</button>
            </div>

            @foreach($itineraryDays as $index => $day)
                <div class="space-y-3 border border-sand-deep/30 p-4" wire:key="itinerary-day-{{ $index }}">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-xs tracking-[0.14em] uppercase text-muted">Day {{ $day['day'] ?? $index + 1 }}</p>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <button type="button" wire:click="moveDayUp({{ $index }})" class="text-muted hover:text-charcoal" @disabled($index === 0)>Up</button>
                            <button type="button" wire:click="moveDayDown({{ $index }})" class="text-muted hover:text-charcoal" @disabled($index === count($itineraryDays) - 1)>Down</button>
                            <button type="button" wire:click="removeDay({{ $index }})" class="text-red-700 hover:underline">Remove</button>
                        </div>
                    </div>

                    <input type="text" wire:model="itineraryDays.{{ $index }}.title" placeholder="Day title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <textarea rows="3" wire:model="itineraryDays.{{ $index }}.description" placeholder="What happens this day" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>

                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-xs tracking-[0.12em] uppercase text-muted mb-2">Lodging (linked stay)</label>
                            <select wire:model="itineraryDays.{{ $index }}.stay_id" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                                <option value="">None / use label below</option>
                                @foreach($stays as $stay)
                                    <option value="{{ $stay->id }}">{{ $stay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs tracking-[0.12em] uppercase text-muted mb-2">Lodging label (fallback)</label>
                            <input type="text" wire:model="itineraryDays.{{ $index }}.stay_name" placeholder="e.g. Entebbe hotel" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs tracking-[0.12em] uppercase text-muted mb-2">Meals</p>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" value="B" wire:model="itineraryDays.{{ $index }}.meals" class="rounded text-forest"> Breakfast
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" value="L" wire:model="itineraryDays.{{ $index }}.meals" class="rounded text-forest"> Lunch
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" value="D" wire:model="itineraryDays.{{ $index }}.meals" class="rounded text-forest"> Dinner
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs tracking-[0.12em] uppercase text-muted mb-2">Day image (optional)</label>
                        @if(!empty($day['image_path']) && empty($removeDayImages[$index]))
                            <div class="mb-2 flex flex-wrap items-center gap-3">
                                <img src="{{ $uploader->url($day['image_path']) }}" alt="" class="h-20 w-28 object-cover">
                                <button type="button" wire:click="clearDayImage({{ $index }})" class="text-sm text-red-700 hover:underline">Remove image</button>
                            </div>
                        @elseif(!empty($removeDayImages[$index]))
                            <p class="mb-2 text-xs text-amber-800">Image will be removed when you save.</p>
                        @endif
                        <input type="file" wire:model="dayImages.{{ $index }}" accept="image/*" class="text-sm w-full">
                        <div wire:loading wire:target="dayImages.{{ $index }}" class="text-xs text-muted mt-1">Uploading…</div>
                        @error('dayImages.'.$index) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            @endforeach
        </section>

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
