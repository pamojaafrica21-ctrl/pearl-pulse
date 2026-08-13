<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Services\ImageUploader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $uploader = app(ImageUploader::class);
        $heroPath = $this->storeHero($uploader);

        $defaults = [
            'hero_tagline' => 'East Africa, beautifully paced.',
            'hero_image' => $heroPath,
            'hero_video_url' => '',
            'featured_eyebrow' => 'Featured journeys',
            'featured_heading' => 'Destinations worth the voyage',
            'featured_intro' => 'Handpicked parks and wildernesses across Uganda, Kenya, Tanzania, and Rwanda.',
            'destinations_eyebrow' => 'East Africa',
            'destinations_heading' => 'Destinations',
            'destinations_intro' => 'From misty gorilla forests to endless savannah — choose your next chapter.',
            'about_title' => 'About Pearl Pulse Safaris',
            'about_content' => '<p>Pearl Pulse Safaris crafts intimate journeys through Uganda, Kenya, Tanzania, and Rwanda. We favour unhurried days, expert local guides, and stays that leave a light footprint.</p><p>From gorilla trekking in Bwindi to the great migrations of the Mara and Serengeti, every itinerary is shaped around how you want to feel on the road — curious, restored, and deeply connected to place.</p>',
            'contact_address' => "Plot 12, Kololo Hill\nKampala, Uganda",
            'contact_phone' => '+256 700 000 000',
            'contact_email' => 'hello@pearlpulse.test',
            'admin_email' => 'admin@pearlpulse.test',
            'social_instagram' => 'https://instagram.com/',
            'social_facebook' => 'https://facebook.com/',
            'social_twitter' => '',
            'footer_blurb' => 'Curated journeys through East Africa’s wildest landscapes.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();
    }

    protected function storeHero(ImageUploader $uploader): string
    {
        $source = storage_path('app/seed-downloads/hero.jpg');
        $existing = Setting::getValue('hero_image');

        if (is_file($source)) {
            if ($existing) {
                $uploader->delete($existing);
            }

            return $uploader->storeFromPath($source, 'site');
        }

        if ($existing) {
            return $existing;
        }

        $path = 'site/hero.jpg';
        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            $img = imagecreatetruecolor(1920, 1080);
            for ($y = 0; $y < 1080; $y++) {
                $ratio = $y / 1080;
                $color = imagecolorallocate($img, (int) (28 + 17 * $ratio), (int) (43 + 27 * $ratio), (int) (31 + 9 * $ratio));
                imageline($img, 0, $y, 1920, $y, $color);
            }
            ob_start();
            imagejpeg($img, null, 85);
            $binary = ob_get_clean();
            imagedestroy($img);
            $disk->put($path, $binary);
        }

        return $path;
    }
}
