<div
    class="enquiry-flow"
    x-data="{
        step: 1,
        total: 7,
        next() { if (this.step < this.total) this.step++ },
        prev() { if (this.step > 1) this.step-- },
        go(n) { this.step = n }
    }"
    x-effect="if (step === 7) { $nextTick(() => window.__enquiryInitEditors?.()) }"
>
    @if($submitted)
        <div class="surface surface--white border border-charcoal/8 px-6 py-8 sm:px-10">
            <p class="section-eyebrow">Enquiry received</p>
            <h2 class="font-display text-3xl text-charcoal mt-2">Thank you</h2>
            <p class="mt-4 text-muted leading-relaxed">We have received your enquiry and will be in touch shortly from Uganda.</p>
        </div>
    @else
        <div class="surface surface--white border border-charcoal/8 px-5 py-8 sm:px-10 sm:py-10">
            @if($journey)
                <p class="text-sm text-forest mb-6">This enquiry is about <strong>{{ $journey->name }}</strong>.</p>
            @endif

            <div class="enquiry-flow__progress" role="list" aria-label="Enquiry progress">
                @for($n = 1; $n <= 7; $n++)
                    <button
                        type="button"
                        class="enquiry-flow__dot"
                        role="listitem"
                        :class="{ 'is-active': step === {{ $n }}, 'is-done': step > {{ $n }} }"
                        @click="go({{ $n }})"
                        :aria-current="step === {{ $n }} ? 'step' : null"
                        aria-label="Step {{ $n }}"
                    ></button>
                @endfor
            </div>

            <form wire:submit="submit">
                <div class="enquiry-flow__step" x-show="step === 1" x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 1 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">Where do you want to go?</h2>
                    <p class="mt-2 text-muted text-sm">Select one or more countries — or tell us somewhere else.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach($countries as $country)
                            <label class="enquiry-chip">
                                <input type="checkbox" value="{{ $country->name }}" wire:model.live="preferredDestinations">
                                {{ $country->name }}
                            </label>
                        @endforeach
                        <label class="enquiry-chip">
                            <input type="checkbox" value="Other" wire:model.live="preferredDestinations">
                            Other
                        </label>
                    </div>
                    @if(in_array('Other', $preferredDestinations, true))
                        <input
                            type="text"
                            wire:model="destinationOther"
                            placeholder="Tell us where else you have in mind"
                            class="mt-5 w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                        >
                    @endif
                </div>

                <div class="enquiry-flow__step" x-show="step === 2" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 2 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">Duration & travellers</h2>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">How many days?</label>
                            <input type="text" wire:model="days" placeholder="e.g. 8–10" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                        </div>
                        <div>
                            <label class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">How many travellers?</label>
                            <input type="text" wire:model="travellers" placeholder="e.g. 2" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                        </div>
                    </div>
                </div>

                <div class="enquiry-flow__step" x-show="step === 3" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 3 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">What experiences interest you?</h2>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach($experiences as $experience)
                            <label class="enquiry-chip">
                                <input type="checkbox" value="{{ $experience->name }}" wire:model.live="preferredExperiences">
                                {{ $experience->name }}
                            </label>
                        @endforeach
                        <label class="enquiry-chip">
                            <input type="checkbox" value="Other" wire:model.live="preferredExperiences">
                            Other
                        </label>
                    </div>
                    @if(in_array('Other', $preferredExperiences, true))
                        <input
                            type="text"
                            wire:model="experienceOther"
                            placeholder="Tell us what else you would like to experience"
                            class="mt-5 w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                        >
                    @endif
                </div>

                <div class="enquiry-flow__step" x-show="step === 4" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 4 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">Accommodation preference</h2>
                    <p class="mt-2 text-muted text-sm">From essential comfort to signature exclusivity — or a considered mix.</p>
                    <div class="mt-8">
                        <select wire:model.live="accommodation" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                            <option value="">Select</option>
                            <option value="Comfortable">Essential</option>
                            <option value="Luxury">Premium</option>
                            <option value="Ultra-luxury">Signature</option>
                            <option value="Mix">A considered mix</option>
                            <option value="Other">Other</option>
                        </select>
                        @if($accommodation === 'Other')
                            <input
                                type="text"
                                wire:model="accommodationOther"
                                placeholder="Describe the stay you prefer"
                                class="mt-4 w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                            >
                        @endif
                    </div>
                </div>

                <div class="enquiry-flow__step" x-show="step === 5" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 5 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">Approximate investment</h2>
                    <p class="mt-2 text-muted text-sm">Price never dominates the journey — this simply helps us propose the right shape.</p>
                    <div class="mt-8">
                        <select wire:model.live="investment" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                            <option value="">Select</option>
                            <option value="Mid-range">Essential quality</option>
                            <option value="Luxury">Premium</option>
                            <option value="Ultra-luxury">Signature / private proposal</option>
                            <option value="Unsure">Not sure yet</option>
                            <option value="Other">Other</option>
                        </select>
                        @if($investment === 'Other')
                            <input
                                type="text"
                                wire:model="investmentOther"
                                placeholder="Share a budget range or notes"
                                class="mt-4 w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                            >
                        @endif
                    </div>
                </div>

                <div class="enquiry-flow__step" x-show="step === 6" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 6 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">Travel dates</h2>
                    <div class="mt-8 grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="enquiry-date-from" class="block text-xs tracking-[0.12em] uppercase mb-2 text-muted">From</label>
                            <input
                                id="enquiry-date-from"
                                type="date"
                                wire:model="travelDateFrom"
                                class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                            >
                            @error('travelDateFrom') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="enquiry-date-to" class="block text-xs tracking-[0.12em] uppercase mb-2 text-muted">To</label>
                            <input
                                id="enquiry-date-to"
                                type="date"
                                wire:model="travelDateTo"
                                class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest"
                            >
                            @error('travelDateTo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="enquiry-flow__step" x-show="step === 7" x-cloak x-transition.opacity>
                    <p class="text-[11px] tracking-[0.18em] uppercase text-muted mb-2">Step 7 of 7</p>
                    <h2 class="font-display text-3xl text-charcoal">How do we reach you?</h2>
                    <div class="mt-8 space-y-5">
                        <div>
                            <label for="enquiry-name" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Name</label>
                            <input id="enquiry-name" type="text" wire:model="name" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="enquiry-email" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Email</label>
                                <input id="enquiry-email" type="email" wire:model="email" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="enquiry-phone" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Phone</label>
                                <input id="enquiry-phone" type="text" wire:model="phone" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">WhatsApp</label>
                            <input type="text" wire:model="whatsapp" class="w-full border-sand-deep/40 bg-white text-charcoal focus:border-forest focus:ring-forest">
                        </div>
                        <div>
                            <label for="enquiry-message-editor" class="block text-xs tracking-[0.14em] uppercase mb-2 text-muted">Tell us more</label>
                            <div wire:ignore class="enquiry-editor">
                                <textarea id="enquiry-message-editor">{!! $message !!}</textarea>
                            </div>
                            @error('message') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="enquiry-flow__nav">
                    <button
                        type="button"
                        class="text-sm tracking-[0.14em] uppercase text-muted hover:text-forest transition disabled:opacity-30"
                        @click="prev()"
                        :disabled="step === 1"
                        :class="step === 1 && 'invisible'"
                    >Back</button>

                    <div class="flex flex-wrap items-center gap-4">
                        <template x-if="step < total">
                            <button type="button" class="btn-primary" @click="next()">Continue</button>
                        </template>
                        <template x-if="step === total">
                            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="submit">Send enquiry</span>
                                <span wire:loading wire:target="submit">Sending…</span>
                            </button>
                        </template>
                    </div>
                </div>
            </form>
        </div>
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
    const editorIds = ['enquiry-message-editor'];
    const fieldMap = {
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

    window.__enquiryInitEditors = initEditors;

    boot();

    $wire.on('enquiry-form-submitted', () => destroyEditors());

    document.addEventListener('livewire:navigating', destroyEditors);
</script>
@endscript
