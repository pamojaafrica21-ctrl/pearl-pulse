<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

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

        return Storage::disk(config('filesystems.uploads', 'public'))->url($path);
    }

    public function contact(): array
    {
        return [
            'address' => $this->get('contact_address', ''),
            'phone' => $this->get('contact_phone', ''),
            'email' => $this->get('contact_email', ''),
            'admin_email' => $this->get('admin_email', config('mail.from.address')),
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
}
