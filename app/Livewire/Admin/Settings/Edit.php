<?php

namespace App\Livewire\Admin\Settings;

use App\Services\ImageUploader;
use App\Services\SettingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public string $hero_tagline = '';

    public string $hero_video_url = '';

    public string $about_title = '';

    public string $about_content = '';

    public string $contact_address = '';

    public string $contact_phone = '';

    public string $contact_email = '';

    public string $admin_email = '';

    public string $social_instagram = '';

    public string $social_facebook = '';

    public string $social_twitter = '';

    public $hero_image;

    public ?string $currentHeroPath = null;

    public function mount(SettingService $settings): void
    {
        $this->hero_tagline = (string) $settings->get('hero_tagline', '');
        $this->hero_video_url = (string) $settings->get('hero_video_url', '');
        $this->about_title = (string) $settings->get('about_title', 'About Pearl Pulse Safaris');
        $this->about_content = (string) $settings->get('about_content', '');
        $this->contact_address = (string) $settings->get('contact_address', '');
        $this->contact_phone = (string) $settings->get('contact_phone', '');
        $this->contact_email = (string) $settings->get('contact_email', '');
        $this->admin_email = (string) $settings->get('admin_email', '');
        $this->social_instagram = (string) $settings->get('social_instagram', '');
        $this->social_facebook = (string) $settings->get('social_facebook', '');
        $this->social_twitter = (string) $settings->get('social_twitter', '');
        $this->currentHeroPath = $settings->get('hero_image');
    }

    protected function rules(): array
    {
        return [
            'hero_tagline' => ['nullable', 'string', 'max:255'],
            'hero_video_url' => ['nullable', 'url', 'max:500'],
            'about_title' => ['nullable', 'string', 'max:180'],
            'about_content' => ['nullable', 'string'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'contact_phone' => ['nullable', 'string', 'max:60'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:500'],
            'social_facebook' => ['nullable', 'url', 'max:500'],
            'social_twitter' => ['nullable', 'url', 'max:500'],
            'hero_image' => ['nullable', 'image', 'max:8192'],
        ];
    }

    public function save(SettingService $settings, ImageUploader $uploader): void
    {
        $this->validate();

        if ($this->hero_image) {
            $uploader->delete($this->currentHeroPath);
            $this->currentHeroPath = $uploader->store($this->hero_image, 'site');
            $settings->set('hero_image', $this->currentHeroPath);
            $this->hero_image = null;
        }

        $settings->set('hero_tagline', $this->hero_tagline);
        $settings->set('hero_video_url', $this->hero_video_url);
        $settings->set('about_title', $this->about_title);
        $settings->set('about_content', $this->about_content);
        $settings->set('contact_address', $this->contact_address);
        $settings->set('contact_phone', $this->contact_phone);
        $settings->set('contact_email', $this->contact_email);
        $settings->set('admin_email', $this->admin_email);
        $settings->set('social_instagram', $this->social_instagram);
        $settings->set('social_facebook', $this->social_facebook);
        $settings->set('social_twitter', $this->social_twitter);

        session()->flash('status', 'Settings saved.');
    }

    public function removeHero(SettingService $settings, ImageUploader $uploader): void
    {
        $uploader->delete($this->currentHeroPath);
        $settings->set('hero_image', '');
        $this->currentHeroPath = null;
    }

    public function render()
    {
        return view('livewire.admin.settings.edit', [
            'uploader' => app(ImageUploader::class),
        ])->layout('layouts.admin', ['heading' => 'Site settings']);
    }
}
