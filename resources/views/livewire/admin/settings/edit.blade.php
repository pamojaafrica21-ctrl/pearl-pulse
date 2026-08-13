<div>
    <form wire:submit="save" class="space-y-10 max-w-3xl">
        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage hero</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Tagline</label>
                <input type="text" wire:model="hero_tagline" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Hero image</label>
                @if($currentHeroPath)
                    <div class="mb-3 flex items-start gap-3">
                        <img src="{{ $uploader->url($currentHeroPath) }}" alt="" class="h-28 w-48 object-cover">
                        <button type="button" wire:click="removeHero" class="text-sm text-red-700">Remove</button>
                    </div>
                @endif
                <input type="file" wire:model="hero_image" accept="image/*" class="text-sm">
                <div wire:loading wire:target="hero_image" class="text-xs text-muted mt-1">Uploading…</div>
                @error('hero_image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Hero video URL (optional)</label>
                <input type="url" wire:model="hero_video_url" placeholder="https://…" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                @error('hero_video_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">About page</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Title</label>
                <input type="text" wire:model="about_title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Content</label>
                <div wire:ignore>
                    <input id="about_content" type="hidden" value="{{ $about_content }}">
                    <trix-editor input="about_content"></trix-editor>
                </div>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Contact</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Address</label>
                <textarea rows="2" wire:model="contact_address" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Phone</label>
                    <input type="text" wire:model="contact_phone" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                </div>
                <div>
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Public email</label>
                    <input type="email" wire:model="contact_email" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                </div>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Admin email (enquiry notifications)</label>
                <input type="email" wire:model="admin_email" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Social links</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Instagram</label>
                <input type="url" wire:model="social_instagram" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Facebook</label>
                <input type="url" wire:model="social_facebook" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">X / Twitter</label>
                <input type="url" wire:model="social_twitter" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
        </section>

        <button type="submit" class="btn-primary text-xs" wire:loading.attr="disabled">Save settings</button>
    </form>
</div>

@script
<script>
    const input = document.getElementById('about_content');
    if (input) {
        input.addEventListener('trix-change', (e) => {
            $wire.set('about_content', e.target.value);
        });
    }
</script>
@endscript
