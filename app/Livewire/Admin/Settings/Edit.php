<?php

namespace App\Livewire\Admin\Settings;

use App\Services\ImageUploader;
use App\Services\SettingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public string $hero_headline = '';

    public string $hero_tagline = '';

    public string $hero_kicker = '';

    public string $contact_whatsapp = '';

    public string $hero_video_url = '';

    public string $hero_video_source = 'url';

    public ?string $currentHeroVideoPath = null;

    public $hero_video = null;

    public bool $removeHeroVideoFile = false;

    public string $home_intro_eyebrow = '';

    public string $home_intro_heading = '';

    public string $home_intro_body = '';

    public array $home_pillars = [];

    public string $featured_eyebrow = '';

    public string $featured_heading = '';

    public string $featured_intro = '';

    public string $home_cta_heading = '';

    public string $home_cta_text = '';

    public string $home_cta_button = '';

    public string $destinations_eyebrow = '';

    public string $destinations_heading = '';

    public string $destinations_intro = '';

    public string $destinations_note_heading = '';

    public string $destinations_note_body = '';

    public string $destinations_cta_heading = '';

    public string $destinations_cta_text = '';

    public string $about_eyebrow = '';

    public string $about_title = '';

    public string $about_lead = '';

    public string $about_content = '';

    public array $about_values = [];

    public string $about_approach_heading = '';

    public string $about_approach_body = '';

    public string $about_cta_heading = '';

    public string $about_cta_text = '';

    public string $footer_blurb = '';

    public string $contact_address = '';

    public string $contact_phone = '';

    public string $contact_email = '';

    public string $admin_email = '';

    public string $social_instagram = '';

    public string $social_facebook = '';

    public string $social_twitter = '';

    public string $review_google_url = '';

    public string $review_tripadvisor_url = '';

    public string $home_destinations_eyebrow = '';

    public string $home_destinations_heading = '';

    public string $home_destinations_intro = '';

    public string $home_experiences_eyebrow = '';

    public string $home_experiences_heading = '';

    public string $home_experiences_intro = '';

    /** @var list<array{label: string, headline: string, tagline: string, video_url: string, video_path: string, video_source: string}> */
    public array $hero_slides = [];

    /** @var array<int, mixed> */
    public array $hero_slide_videos = [];

    /** @var array<int, bool> */
    public array $hero_slide_remove_videos = [];

    public $hero_image;

    public ?string $currentHeroPath = null;

    public function mount(SettingService $settings): void
    {
        $this->hero_headline = (string) $settings->get('hero_headline', '');
        $this->hero_tagline = (string) $settings->get('hero_tagline', '');
        $this->hero_kicker = (string) $settings->get('hero_kicker', '');
        $this->hero_video_url = (string) $settings->get('hero_video_url', '');
        $this->currentHeroVideoPath = $settings->get('hero_video_path') ?: null;
        $this->hero_video_source = $this->normalizeVideoSource(
            (string) $settings->get('hero_video_source', ''),
            $this->currentHeroVideoPath,
            $this->hero_video_url
        );
        $this->hero_slides = $this->decodeHeroSlides($settings->get('hero_slides'));
        $this->hero_slide_videos = [null, null, null, null];
        $this->hero_slide_remove_videos = [false, false, false, false];
        $this->home_intro_eyebrow = (string) $settings->get('home_intro_eyebrow', '');
        $this->home_intro_heading = (string) $settings->get('home_intro_heading', '');
        $this->home_intro_body = (string) $settings->get('home_intro_body', '');
        $this->home_pillars = $this->decodeList($settings->get('home_pillars'), 5);
        $this->home_destinations_eyebrow = (string) $settings->get('home_destinations_eyebrow', 'Destinations');
        $this->home_destinations_heading = (string) $settings->get('home_destinations_heading', 'Where do you want to go?');
        $this->home_destinations_intro = (string) $settings->get('home_destinations_intro', '');
        $this->home_experiences_eyebrow = (string) $settings->get('home_experiences_eyebrow', 'Experiences');
        $this->home_experiences_heading = (string) $settings->get('home_experiences_heading', 'What kind of trip are you looking for?');
        $this->home_experiences_intro = (string) $settings->get('home_experiences_intro', '');
        $this->featured_eyebrow = (string) $settings->get('featured_eyebrow', 'Featured journeys');
        $this->featured_heading = (string) $settings->get('featured_heading', 'Destinations worth the voyage');
        $this->featured_intro = (string) $settings->get('featured_intro', '');
        $this->home_cta_heading = (string) $settings->get('home_cta_heading', '');
        $this->home_cta_text = (string) $settings->get('home_cta_text', '');
        $this->home_cta_button = (string) $settings->get('home_cta_button', 'Plan your journey');
        $this->destinations_eyebrow = (string) $settings->get('destinations_eyebrow', 'East Africa');
        $this->destinations_heading = (string) $settings->get('destinations_heading', 'Destinations');
        $this->destinations_intro = (string) $settings->get('destinations_intro', '');
        $this->destinations_note_heading = (string) $settings->get('destinations_note_heading', '');
        $this->destinations_note_body = (string) $settings->get('destinations_note_body', '');
        $this->destinations_cta_heading = (string) $settings->get('destinations_cta_heading', '');
        $this->destinations_cta_text = (string) $settings->get('destinations_cta_text', '');
        $this->about_eyebrow = (string) $settings->get('about_eyebrow', 'Our story');
        $this->about_title = (string) $settings->get('about_title', 'About Pearl Pulse Safaris');
        $this->about_lead = (string) $settings->get('about_lead', '');
        $this->about_content = (string) $settings->get('about_content', '');
        $this->about_values = $this->decodeList($settings->get('about_values'), 3);
        $this->about_approach_heading = (string) $settings->get('about_approach_heading', '');
        $this->about_approach_body = (string) $settings->get('about_approach_body', '');
        $this->about_cta_heading = (string) $settings->get('about_cta_heading', '');
        $this->about_cta_text = (string) $settings->get('about_cta_text', '');
        $this->footer_blurb = (string) $settings->get('footer_blurb', '');
        $this->contact_address = (string) $settings->get('contact_address', '');
        $this->contact_phone = (string) $settings->get('contact_phone', '');
        $this->contact_whatsapp = (string) $settings->get('contact_whatsapp', '');
        $this->contact_email = (string) $settings->get('contact_email', '');
        $this->admin_email = (string) $settings->get('admin_email', '');
        $this->social_instagram = (string) $settings->get('social_instagram', '');
        $this->social_facebook = (string) $settings->get('social_facebook', '');
        $this->social_twitter = (string) $settings->get('social_twitter', '');
        $this->review_google_url = (string) $settings->get('review_google_url', '');
        $this->review_tripadvisor_url = (string) $settings->get('review_tripadvisor_url', '');
        $this->currentHeroPath = $settings->get('hero_image');
    }

    protected function decodeHeroSlides(mixed $value): array
    {
        $items = [];

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        } elseif (is_array($value)) {
            $items = $value;
        }

        $normalized = [];
        for ($i = 0; $i < 4; $i++) {
            $videoUrl = (string) ($items[$i]['video_url'] ?? '');
            $videoPath = (string) ($items[$i]['video_path'] ?? '');
            $normalized[] = [
                'label' => (string) ($items[$i]['label'] ?? ''),
                'headline' => (string) ($items[$i]['headline'] ?? ''),
                'tagline' => (string) ($items[$i]['tagline'] ?? ''),
                'video_url' => $videoUrl,
                'video_path' => $videoPath,
                'video_source' => $this->normalizeVideoSource(
                    (string) ($items[$i]['video_source'] ?? ''),
                    $videoPath !== '' ? $videoPath : null,
                    $videoUrl
                ),
            ];
        }

        return $normalized;
    }

    protected function normalizeVideoSource(string $source, ?string $path, string $url): string
    {
        if (in_array($source, ['upload', 'url'], true)) {
            return $source;
        }

        return filled($path) ? 'upload' : 'url';
    }

    protected function decodeList(mixed $value, int $count): array
    {
        $items = [];

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        } elseif (is_array($value)) {
            $items = $value;
        }

        $normalized = [];
        for ($i = 0; $i < $count; $i++) {
            $normalized[] = [
                'title' => (string) ($items[$i]['title'] ?? ''),
                'text' => (string) ($items[$i]['text'] ?? ''),
            ];
        }

        return $normalized;
    }

    protected function rules(): array
    {
        return [
            'hero_headline' => ['nullable', 'string', 'max:255'],
            'hero_tagline' => ['nullable', 'string', 'max:255'],
            'hero_kicker' => ['nullable', 'string', 'max:180'],
            'contact_whatsapp' => ['nullable', 'string', 'max:60'],
            'hero_video_url' => ['nullable', 'url', 'max:500'],
            'hero_video_source' => ['required', 'in:upload,url'],
            'hero_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'removeHeroVideoFile' => ['boolean'],
            'hero_slides' => ['array'],
            'hero_slides.*.label' => ['nullable', 'string', 'max:120'],
            'hero_slides.*.headline' => ['nullable', 'string', 'max:255'],
            'hero_slides.*.tagline' => ['nullable', 'string', 'max:500'],
            'hero_slides.*.video_url' => ['nullable', 'url', 'max:500'],
            'hero_slides.*.video_path' => ['nullable', 'string', 'max:500'],
            'hero_slides.*.video_source' => ['required', 'in:upload,url'],
            'hero_slide_videos' => ['array'],
            'hero_slide_videos.*' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:102400'],
            'hero_slide_remove_videos' => ['array'],
            'hero_slide_remove_videos.*' => ['boolean'],
            'home_intro_eyebrow' => ['nullable', 'string', 'max:120'],
            'home_intro_heading' => ['nullable', 'string', 'max:180'],
            'home_intro_body' => ['nullable', 'string', 'max:2000'],
            'home_pillars' => ['array'],
            'home_pillars.*.title' => ['nullable', 'string', 'max:120'],
            'home_pillars.*.text' => ['nullable', 'string', 'max:500'],
            'home_destinations_eyebrow' => ['nullable', 'string', 'max:120'],
            'home_destinations_heading' => ['nullable', 'string', 'max:180'],
            'home_destinations_intro' => ['nullable', 'string', 'max:500'],
            'home_experiences_eyebrow' => ['nullable', 'string', 'max:120'],
            'home_experiences_heading' => ['nullable', 'string', 'max:180'],
            'home_experiences_intro' => ['nullable', 'string', 'max:500'],
            'featured_eyebrow' => ['nullable', 'string', 'max:120'],
            'featured_heading' => ['nullable', 'string', 'max:180'],
            'featured_intro' => ['nullable', 'string', 'max:500'],
            'home_cta_heading' => ['nullable', 'string', 'max:180'],
            'home_cta_text' => ['nullable', 'string', 'max:500'],
            'home_cta_button' => ['nullable', 'string', 'max:80'],
            'destinations_eyebrow' => ['nullable', 'string', 'max:120'],
            'destinations_heading' => ['nullable', 'string', 'max:180'],
            'destinations_intro' => ['nullable', 'string', 'max:500'],
            'destinations_note_heading' => ['nullable', 'string', 'max:180'],
            'destinations_note_body' => ['nullable', 'string', 'max:2000'],
            'destinations_cta_heading' => ['nullable', 'string', 'max:180'],
            'destinations_cta_text' => ['nullable', 'string', 'max:500'],
            'about_eyebrow' => ['nullable', 'string', 'max:120'],
            'about_title' => ['nullable', 'string', 'max:180'],
            'about_lead' => ['nullable', 'string', 'max:500'],
            'about_content' => ['nullable', 'string'],
            'about_values' => ['array'],
            'about_values.*.title' => ['nullable', 'string', 'max:120'],
            'about_values.*.text' => ['nullable', 'string', 'max:500'],
            'about_approach_heading' => ['nullable', 'string', 'max:180'],
            'about_approach_body' => ['nullable', 'string', 'max:2000'],
            'about_cta_heading' => ['nullable', 'string', 'max:180'],
            'about_cta_text' => ['nullable', 'string', 'max:500'],
            'footer_blurb' => ['nullable', 'string', 'max:500'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:500'],
            'social_facebook' => ['nullable', 'url', 'max:500'],
            'social_twitter' => ['nullable', 'url', 'max:500'],
            'review_google_url' => ['nullable', 'url', 'max:500'],
            'review_tripadvisor_url' => ['nullable', 'url', 'max:500'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
        ];
    }

    public function save(SettingService $settings, ImageUploader $uploader, \App\Services\VideoUploader $videos): void
    {
        $this->validate();

        if ($this->hero_image) {
            $uploader->delete($this->currentHeroPath);
            $this->currentHeroPath = $uploader->store($this->hero_image, 'site');
            $settings->set('hero_image', $this->currentHeroPath);
            $this->hero_image = null;
        }

        if ($this->removeHeroVideoFile && $this->currentHeroVideoPath) {
            $videos->delete($this->currentHeroVideoPath);
            $this->currentHeroVideoPath = null;
            $this->removeHeroVideoFile = false;
        }

        if ($this->hero_video) {
            $videos->delete($this->currentHeroVideoPath);
            $this->currentHeroVideoPath = $videos->store($this->hero_video, 'site/hero');
            $this->hero_video = null;
            $this->hero_video_source = 'upload';
        }

        $settings->set('hero_video_path', $this->currentHeroVideoPath ?? '');
        $settings->set('hero_video_source', $this->hero_video_source);

        $map = [
            'hero_headline', 'hero_tagline', 'hero_kicker', 'hero_video_url',
            'home_intro_eyebrow', 'home_intro_heading', 'home_intro_body',
            'home_destinations_eyebrow', 'home_destinations_heading', 'home_destinations_intro',
            'home_experiences_eyebrow', 'home_experiences_heading', 'home_experiences_intro',
            'featured_eyebrow', 'featured_heading', 'featured_intro',
            'home_cta_heading', 'home_cta_text', 'home_cta_button',
            'destinations_eyebrow', 'destinations_heading', 'destinations_intro',
            'destinations_note_heading', 'destinations_note_body',
            'destinations_cta_heading', 'destinations_cta_text',
            'about_eyebrow', 'about_title', 'about_lead', 'about_content',
            'about_approach_heading', 'about_approach_body',
            'about_cta_heading', 'about_cta_text',
            'footer_blurb',
            'contact_address', 'contact_phone', 'contact_whatsapp', 'contact_email', 'admin_email',
            'social_instagram', 'social_facebook', 'social_twitter',
            'review_google_url', 'review_tripadvisor_url',
        ];

        foreach ($map as $key) {
            $settings->set($key, $this->{$key});
        }

        $slides = [];
        foreach ($this->hero_slides as $index => $slide) {
            $videoPath = (string) ($slide['video_path'] ?? '');

            if (! empty($this->hero_slide_remove_videos[$index]) && $videoPath !== '') {
                $videos->delete($videoPath);
                $videoPath = '';
            }

            if (! empty($this->hero_slide_videos[$index])) {
                $videos->delete($videoPath !== '' ? $videoPath : null);
                $videoPath = $videos->store($this->hero_slide_videos[$index], 'site/hero/'.$index);
                $slide['video_source'] = 'upload';
            }

            $slides[] = [
                'label' => (string) ($slide['label'] ?? ''),
                'headline' => (string) ($slide['headline'] ?? ''),
                'tagline' => (string) ($slide['tagline'] ?? ''),
                'video_url' => (string) ($slide['video_url'] ?? ''),
                'video_path' => $videoPath,
                'video_source' => $this->normalizeVideoSource(
                    (string) ($slide['video_source'] ?? ''),
                    $videoPath !== '' ? $videoPath : null,
                    (string) ($slide['video_url'] ?? '')
                ),
            ];
        }

        $this->hero_slides = $slides;
        $this->hero_slide_videos = [null, null, null, null];
        $this->hero_slide_remove_videos = [false, false, false, false];

        $settings->set('home_pillars', json_encode(array_values($this->home_pillars)));
        $settings->set('about_values', json_encode(array_values($this->about_values)));
        $settings->set('hero_slides', json_encode(array_values($slides)));

        session()->flash('status', 'Settings saved.');
    }

    public function removeHero(SettingService $settings, ImageUploader $uploader): void
    {
        $uploader->delete($this->currentHeroPath);
        $settings->set('hero_image', '');
        $this->currentHeroPath = null;
    }

    public function clearHeroVideo(): void
    {
        $this->hero_video = null;
        $this->removeHeroVideoFile = true;
    }

    public function clearHeroSlideVideo(int $index): void
    {
        $this->hero_slide_videos[$index] = null;
        $this->hero_slide_remove_videos[$index] = true;
    }

    public function render()
    {
        return view('livewire.admin.settings.edit', [
            'uploader' => app(ImageUploader::class),
            'videoUploader' => app(\App\Services\VideoUploader::class),
        ])->layout('layouts.admin', ['heading' => 'Site settings']);
    }
}
