<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    public function get(string $key, mixed $default = null): mixed
    {
        return Setting::getValue($key, $default);
    }

    public function set(string $key, mixed $value): void
    {
        Setting::setValue($key, $value);
    }

    public function many(array $keys): array
    {
        $result = [];

        foreach ($keys as $key => $default) {
            if (is_int($key)) {
                $result[$default] = $this->get($default);
            } else {
                $result[$key] = $this->get($key, $default);
            }
        }

        return $result;
    }

    public function heroImageUrl(): ?string
    {
        $path = $this->get('hero_image');

        if (! $path) {
            return null;
        }

        return app(ImageUploader::class)->url($path);
    }

    public function contact(): array
    {
        return [
            'address' => $this->get('contact_address', ''),
            'phone' => $this->get('contact_phone', ''),
            'email' => $this->get('contact_email', ''),
            'admin_email' => $this->get('admin_email', config('mail.from.address')),
            'whatsapp' => $this->get('contact_whatsapp', ''),
        ];
    }

    public function social(): array
    {
        return [
            'instagram' => $this->get('social_instagram', ''),
            'facebook' => $this->get('social_facebook', ''),
            'twitter' => $this->get('social_twitter', ''),
        ];
    }

    public function reviewLinks(): array
    {
        return [
            'google' => $this->get('review_google_url', ''),
            'tripadvisor' => $this->get('review_tripadvisor_url', ''),
        ];
    }

    /**
     * Homepage rotating hero slides. Falls back to legacy single hero fields.
     *
     * @return list<array{label: string, headline: string, tagline: string, video_url: string, image_url: ?string}>
     */
    public function heroSlides(): array
    {
        $raw = $this->get('hero_slides', '[]');
        $items = is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
        $images = app(ImageUploader::class);
        $videos = app(VideoUploader::class);
        $slides = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $label = trim((string) ($item['label'] ?? ''));
            $headline = trim((string) ($item['headline'] ?? ''));
            $tagline = trim((string) ($item['tagline'] ?? ''));
            $videoUrl = trim((string) ($item['video_url'] ?? ''));
            $videoPath = trim((string) ($item['video_path'] ?? ''));
            $imagePath = trim((string) ($item['image_path'] ?? ''));
            $source = (string) ($item['video_source'] ?? '');

            $resolvedVideo = $this->resolveHeroVideoUrl($source, $videoPath, $videoUrl, $videos);

            if ($label === '' && $headline === '' && $resolvedVideo === '' && $imagePath === '') {
                continue;
            }

            $slides[] = [
                'label' => $label,
                'headline' => $headline,
                'tagline' => $tagline,
                'video_url' => $resolvedVideo,
                'image_url' => $imagePath !== '' ? $images->url($imagePath) : null,
            ];
        }

        if ($slides !== []) {
            return $slides;
        }

        $fallbackPath = trim((string) $this->get('hero_video_path', ''));
        $fallbackUrl = trim((string) $this->get('hero_video_url', ''));
        $fallbackSource = (string) $this->get('hero_video_source', '');

        return [[
            'label' => (string) $this->get('hero_kicker', ''),
            'headline' => (string) $this->get('hero_headline', 'Private journeys into Africa’s wild heart'),
            'tagline' => (string) $this->get('hero_tagline', 'Private, tailor-made journeys shaped around how you want to experience Africa.'),
            'video_url' => $this->resolveHeroVideoUrl($fallbackSource, $fallbackPath, $fallbackUrl, $videos),
            'image_url' => $this->heroImageUrl(),
        ]];
    }

    protected function resolveHeroVideoUrl(string $source, string $path, string $url, VideoUploader $videos): string
    {
        $preferUpload = $source === 'upload' || ($source === '' && $path !== '');

        if ($preferUpload && $path !== '') {
            return (string) ($videos->url($path) ?? '');
        }

        if ($url !== '') {
            return $url;
        }

        if ($path !== '') {
            return (string) ($videos->url($path) ?? '');
        }

        return '';
    }

    public function isSitePublic(): bool
    {
        return $this->get('site_public', '1') === '1';
    }

    public function setSitePublic(bool $public): void
    {
        $this->set('site_public', $public ? '1' : '0');
    }

    public function whatsapp(): string
    {
        return preg_replace('/\D+/', '', (string) $this->get('contact_whatsapp', $this->get('contact_phone', ''))) ?: '';
    }

    public function whatsappUrl(?string $message = null): ?string
    {
        $number = $this->whatsapp();
        if ($number === '') {
            return null;
        }

        $url = 'https://wa.me/'.$number;

        return $message ? $url.'?text='.rawurlencode($message) : $url;
    }

    /**
     * @return list<array{title: string, text: string}>
     */
    public function listItems(string $key, int $count = 6): array
    {
        $raw = $this->get($key, '[]');
        $items = is_array($raw) ? $raw : (json_decode((string) $raw, true) ?: []);
        $normalized = [];

        for ($i = 0; $i < $count; $i++) {
            $title = trim((string) ($items[$i]['title'] ?? ''));
            $text = trim((string) ($items[$i]['text'] ?? ''));
            if ($title === '' && $text === '') {
                continue;
            }
            $normalized[] = ['title' => $title, 'text' => $text];
        }

        return $normalized;
    }
}
