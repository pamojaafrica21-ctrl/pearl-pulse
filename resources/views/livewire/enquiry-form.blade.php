<div>
    @if($submitted)
        <div class="rounded-sm border border-sand/30 bg-sand/10 px-5 py-4 text-sm">
            Thank you — we have received your enquiry and will be in touch shortly.
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            @if($journey)
                <p class="text-sm text-forest">This enquiry is about <strong>{{ $journey->name }}</strong>.</p>
            @endif

            <div>
                <p class="text-xs tracking-[0.14em] uppercase mb-3 opacity-70">Where do you want to go?</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($countries as $country)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $country->name }}" wire:model.live="preferredDestinations" class="rounded border-sand-deep text-forest focus:ring-forest">
                            {{ $country->name }}
                        </label>
                    @endforeach
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" value="Other" wire:model.live="preferredDestinations" class="rounded border-sand-deep text-forest focus:ring-forest">
                        Other
                    </label>
                </div>
                @if(in_array('Other', $preferredDestinations, true))
                    <input
                        type="text"
                        wire:model="destinationOther"
                        placeholder="Tell us where else you have in mind"
                        class="mt-3 w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                    >
                @endif
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">How many days?</label>
                    <input type="text" wire:model="days" placeholder="e.g. 8–10" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">How many travellers?</label>
                    <input type="text" wire:model="travellers" placeholder="e.g. 2" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                </div>
            </div>

            <div>
                <p class="text-xs tracking-[0.14em] uppercase mb-3 opacity-70">What experiences interest you?</p>
                <div class="grid gap-2 sm:grid-cols-2">
                    @foreach($experiences as $experience)
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" value="{{ $experience->name }}" wire:model.live="preferredExperiences" class="rounded border-sand-deep text-forest focus:ring-forest">
                            {{ $experience->name }}
                        </label>
                    @endforeach
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" value="Other" wire:model.live="preferredExperiences" class="rounded border-sand-deep text-forest focus:ring-forest">
                        Other
                    </label>
                </div>
                @if(in_array('Other', $preferredExperiences, true))
                    <input
                        type="text"
                        wire:model="experienceOther"
                        placeholder="Tell us what else you would like to experience"
                        class="mt-3 w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                    >
                @endif
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Accommodation preference</label>
                    <select wire:model.live="accommodation" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                        <option value="">Select</option>
                        <option value="Comfortable">Comfortable / mid-range</option>
                        <option value="Luxury">Luxury</option>
                        <option value="Ultra-luxury">Ultra-luxury</option>
                        <option value="Mix">A considered mix</option>
                        <option value="Other">Other</option>
                    </select>
                    @if($accommodation === 'Other')
                        <input
                            type="text"
                            wire:model="accommodationOther"
                            placeholder="Describe the stay you prefer"
                            class="mt-3 w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                        >
                    @endif
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Approximate investment</label>
                    <select wire:model.live="investment" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                        <option value="">Select</option>
                        <option value="Mid-range">Mid-range quality</option>
                        <option value="Luxury">Luxury</option>
                        <option value="Ultra-luxury">Ultra-luxury / private proposal</option>
                        <option value="Unsure">Not sure yet</option>
                        <option value="Other">Other</option>
                    </select>
                    @if($investment === 'Other')
                        <input
                            type="text"
                            wire:model="investmentOther"
                            placeholder="Share a budget range or notes"
                            class="mt-3 w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                        >
                    @endif
                </div>
            </div>

            <div>
                <p class="text-xs tracking-[0.14em] uppercase mb-3 opacity-70">Travel dates</p>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="enquiry-date-from" class="block text-xs tracking-[0.12em] uppercase mb-2 opacity-60">From</label>
                        <input
                            id="enquiry-date-from"
                            type="date"
                            wire:model="travelDateFrom"
                            class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                        >
                        @error('travelDateFrom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="enquiry-date-to" class="block text-xs tracking-[0.12em] uppercase mb-2 opacity-60">To</label>
                        <input
                            id="enquiry-date-to"
                            type="date"
                            wire:model="travelDateTo"
                            class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest"
                        >
                        @error('travelDateTo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="enquiry-preferences-editor" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Additional preferences</label>
                <div wire:ignore class="enquiry-editor">
                    <textarea id="enquiry-preferences-editor">{!! $preferences !!}</textarea>
                </div>
                @error('preferences') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="enquiry-name" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Name</label>
                <input id="enquiry-name" type="text" wire:model="name" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="enquiry-email" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Email</label>
                    <input id="enquiry-email" type="email" wire:model="email" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="enquiry-phone" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Phone</label>
                    <input id="enquiry-phone" type="text" wire:model="phone" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
                </div>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">WhatsApp</label>
                <input type="text" wire:model="whatsapp" class="w-full border-sand-deep/40 bg-white/90 text-charcoal focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label for="enquiry-message-editor" class="block text-xs tracking-[0.14em] uppercase mb-2 opacity-70">Tell us more</label>
                <div wire:ignore class="enquiry-editor">
                    <textarea id="enquiry-message-editor">{!! $message !!}</textarea>
                </div>
                @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Send enquiry</span>
                <span wire:loading wire:target="submit">Sending…</span>
            </button>
        </form>
    @endif
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.1/tinymce.min.js" referrerpolicy="origin"></script>
<style>
    .enquiry-editor .tox-tinymce {
        border-radius: 2px;
        border-color: rgb(212 196 168 / 0.55) !important;
    }
    .enquiry-editor .tox .tox-toolbar__primary {
        background: #f7f3eb;
    }
</style>
@endassets

@script
<script>
    const editorIds = ['enquiry-preferences-editor', 'enquiry-message-editor'];
    const fieldMap = {
        'enquiry-preferences-editor': 'preferences',
        'enquiry-message-editor': 'message',
    };

    const destroyEditors = () => {
        editorIds.forEach((id) => {
            const existing = window.tinymce?.get(id);
            if (existing) existing.remove();
        });
    };

    const initEditors = () => {
        if (!window.tinymce) return;

        editorIds.forEach((id) => {
            if (!document.getElementById(id) || window.tinymce.get(id)) return;

            window.tinymce.init({
                selector: `#${id}`,
                license_key: 'gpl',
                base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7.6.1',
                suffix: '.min',
                menubar: false,
                branding: false,
                promotion: false,
                plugins: 'lists link autoresize',
                toolbar: 'bold italic underline | bullist numlist | link | removeformat',
                min_height: id.includes('message') ? 220 : 160,
                max_height: 420,
                skin: 'oxide',
                content_css: 'default',
                content_style: 'body { font-family: Outfit, ui-sans-serif, system-ui, sans-serif; font-size: 15px; color: #2c2c28; line-height: 1.5; }',
                setup: (editor) => {
                    const sync = () => $wire.set(fieldMap[id], editor.getContent());
                    editor.on('init change keyup blur Undo Redo SetContent', sync);
                },
            });
        });
    };

    const boot = () => {
        if (window.tinymce) {
            initEditors();
            return;
        }

        const wait = setInterval(() => {
            if (!window.tinymce) return;
            clearInterval(wait);
            initEditors();
        }, 50);
    };

    boot();

    $wire.on('enquiry-form-submitted', () => destroyEditors());

    document.addEventListener('livewire:navigating', destroyEditors);
</script>
@endscript
