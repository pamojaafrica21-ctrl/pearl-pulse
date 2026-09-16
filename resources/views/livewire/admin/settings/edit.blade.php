<div>
    <form wire:submit="save" class="space-y-10 max-w-3xl">
        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage hero</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Headline</label>
                <input type="text" wire:model="hero_headline" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Countries line</label>
                <input type="text" wire:model="hero_kicker" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
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
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Hero video (optional fallback)</label>
                <p class="mb-3 text-xs text-muted">Used when rotating slides below are empty. Choose uploaded file or a YouTube / MP4 URL.</p>
                <div class="mb-3 flex flex-wrap gap-4 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model.live="hero_video_source" value="upload" class="border-sand-deep/40 text-forest focus:ring-forest">
                        Uploaded video
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" wire:model.live="hero_video_source" value="url" class="border-sand-deep/40 text-forest focus:ring-forest">
                        YouTube or URL
                    </label>
                </div>
                @if($hero_video_source === 'upload')
                    @if($currentHeroVideoPath && ! $removeHeroVideoFile)
                        <div class="mb-2 flex flex-wrap items-center gap-3 text-sm">
                            <span class="inline-flex items-center gap-2 rounded-sm bg-forest/10 px-2 py-1 text-forest">Video file attached</span>
                            <a href="{{ $videoUploader->url($currentHeroVideoPath) }}" target="_blank" rel="noopener" class="text-forest hover:underline">Preview</a>
                            <button type="button" wire:click="clearHeroVideo" class="text-red-700 hover:underline">Remove file</button>
                        </div>
                    @elseif($removeHeroVideoFile)
                        <p class="mb-2 text-xs text-amber-800">Video file will be removed when you save.</p>
                    @endif
                    <input type="file" wire:model="hero_video" accept="video/mp4,video/webm,video/quicktime" class="text-sm w-full">
                    <div wire:loading wire:target="hero_video" class="text-xs text-muted mt-1">Uploading video…</div>
                    @error('hero_video') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @else
                    <input type="url" wire:model="hero_video_url" placeholder="https://www.youtube.com/watch?v=…" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    @error('hero_video_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                @endif
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Rotating hero slides</h2>
            <p class="text-sm text-muted">Up to four cinematic slides (destination label, headline, tagline, optional video). Leave blank to use the single hero above.</p>
            @foreach($hero_slides as $index => $slide)
                <div class="space-y-3 border border-sand-deep/30 p-4" wire:key="hero-slide-{{ $index }}">
                    <p class="text-xs tracking-[0.14em] uppercase text-muted">Slide {{ $index + 1 }}</p>
                    <input type="text" wire:model="hero_slides.{{ $index }}.label" placeholder="Destination label (e.g. Kenya)" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <input type="text" wire:model="hero_slides.{{ $index }}.headline" placeholder="Headline" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <input type="text" wire:model="hero_slides.{{ $index }}.tagline" placeholder="Tagline" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">

                    <div class="space-y-2 pt-1">
                        <p class="text-xs tracking-[0.12em] uppercase text-muted">Slide video</p>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" wire:model.live="hero_slides.{{ $index }}.video_source" value="upload" class="border-sand-deep/40 text-forest focus:ring-forest">
                                Uploaded video
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" wire:model.live="hero_slides.{{ $index }}.video_source" value="url" class="border-sand-deep/40 text-forest focus:ring-forest">
                                YouTube or URL
                            </label>
                        </div>
                        @if(($slide['video_source'] ?? 'url') === 'upload')
                            @if(!empty($slide['video_path']) && empty($hero_slide_remove_videos[$index]))
                                <div class="flex flex-wrap items-center gap-3 text-sm">
                                    <span class="inline-flex items-center gap-2 rounded-sm bg-forest/10 px-2 py-1 text-forest">Video file attached</span>
                                    <a href="{{ $videoUploader->url($slide['video_path']) }}" target="_blank" rel="noopener" class="text-forest hover:underline">Preview</a>
                                    <button type="button" wire:click="clearHeroSlideVideo({{ $index }})" class="text-red-700 hover:underline">Remove file</button>
                                </div>
                            @elseif(!empty($hero_slide_remove_videos[$index]))
                                <p class="text-xs text-amber-800">Video file will be removed when you save.</p>
                            @endif
                            <input type="file" wire:model="hero_slide_videos.{{ $index }}" accept="video/mp4,video/webm,video/quicktime" class="text-sm w-full">
                            <div wire:loading wire:target="hero_slide_videos.{{ $index }}" class="text-xs text-muted">Uploading video…</div>
                            @error('hero_slide_videos.'.$index) <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        @else
                            <input type="url" wire:model="hero_slides.{{ $index }}.video_url" placeholder="YouTube or MP4 URL (optional)" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                            @error('hero_slides.'.$index.'.video_url') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        @endif
                    </div>
                </div>
            @endforeach
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage intro</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Eyebrow</label>
                <input type="text" wire:model="home_intro_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Heading</label>
                <input type="text" wire:model="home_intro_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Body</label>
                <textarea rows="4" wire:model="home_intro_body" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage destinations &amp; experiences copy</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Destinations eyebrow</label>
                <input type="text" wire:model="home_destinations_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Destinations heading</label>
                <input type="text" wire:model="home_destinations_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Destinations intro</label>
                <textarea rows="2" wire:model="home_destinations_intro" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Experiences eyebrow</label>
                <input type="text" wire:model="home_experiences_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Experiences heading</label>
                <input type="text" wire:model="home_experiences_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Experiences intro</label>
                <textarea rows="2" wire:model="home_experiences_intro" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage pillars</h2>
            <p class="text-sm text-muted">Why Pearl Pulse points on the homepage.</p>
            @foreach($home_pillars as $index => $pillar)
                <div class="space-y-3 border-t border-sand-deep/30 pt-4" wire:key="home-pillar-{{ $index }}">
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Pillar {{ $index + 1 }} title</label>
                    <input type="text" wire:model="home_pillars.{{ $index }}.title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Pillar {{ $index + 1 }} text</label>
                    <textarea rows="2" wire:model="home_pillars.{{ $index }}.text" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
                </div>
            @endforeach
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage featured section</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Eyebrow</label>
                <input type="text" wire:model="featured_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Heading</label>
                <input type="text" wire:model="featured_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Intro</label>
                <textarea rows="2" wire:model="featured_intro" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Homepage CTA</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Heading</label>
                <input type="text" wire:model="home_cta_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Text</label>
                <textarea rows="2" wire:model="home_cta_text" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Button label</label>
                <input type="text" wire:model="home_cta_button" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Destinations listing page</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Eyebrow</label>
                <input type="text" wire:model="destinations_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Heading</label>
                <input type="text" wire:model="destinations_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Intro</label>
                <textarea rows="2" wire:model="destinations_intro" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Note heading</label>
                <input type="text" wire:model="destinations_note_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Note body</label>
                <textarea rows="4" wire:model="destinations_note_body" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">CTA heading</label>
                <input type="text" wire:model="destinations_cta_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">CTA text</label>
                <textarea rows="2" wire:model="destinations_cta_text" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">About page</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Eyebrow</label>
                <input type="text" wire:model="about_eyebrow" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Title</label>
                <input type="text" wire:model="about_title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Lead</label>
                <textarea rows="2" wire:model="about_lead" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Main content</label>
                <div wire:ignore>
                    <input id="about_content" type="hidden" value="{{ $about_content }}">
                    <trix-editor input="about_content"></trix-editor>
                </div>
            </div>
            <p class="text-sm text-muted pt-2">Three values shown below the main story.</p>
            @foreach($about_values as $index => $value)
                <div class="space-y-3 border-t border-sand-deep/30 pt-4" wire:key="about-value-{{ $index }}">
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Value {{ $index + 1 }} title</label>
                    <input type="text" wire:model="about_values.{{ $index }}.title" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Value {{ $index + 1 }} text</label>
                    <textarea rows="2" wire:model="about_values.{{ $index }}.text" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
                </div>
            @endforeach
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Approach heading</label>
                <input type="text" wire:model="about_approach_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Approach body</label>
                <textarea rows="4" wire:model="about_approach_body" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">CTA heading</label>
                <input type="text" wire:model="about_cta_heading" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">CTA text</label>
                <textarea rows="2" wire:model="about_cta_text" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
            </div>
        </section>

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Footer</h2>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Blurb</label>
                <textarea rows="2" wire:model="footer_blurb" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest"></textarea>
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
                    <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">WhatsApp</label>
                    <input type="text" wire:model="contact_whatsapp" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
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

        <section class="space-y-5">
            <h2 class="font-display text-2xl text-forest">Review links</h2>
            <p class="text-sm text-muted">Shown in the site header utility bar and homepage trust section.</p>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Google reviews URL</label>
                <input type="url" wire:model="review_google_url" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
            </div>
            <div>
                <label class="block text-xs tracking-[0.14em] uppercase text-muted mb-2">Tripadvisor URL</label>
                <input type="url" wire:model="review_tripadvisor_url" class="w-full border-sand-deep/40 focus:border-forest focus:ring-forest">
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
