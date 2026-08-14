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
            'home_intro_eyebrow' => 'Our approach',
            'home_intro_heading' => 'Travel that feels considered',
            'home_intro_body' => "We design safaris around pace, place, and the people who know the land best. Fewer transfers, deeper stays, and guiding that leaves room for wonder.\n\nWhether you are drawn to gorilla forests or migration plains, we shape each journey to match how you want to move through East Africa.",
            'home_pillars' => json_encode([
                ['title' => 'Local expertise', 'text' => 'Guides and partners rooted in Uganda, Kenya, Tanzania, and Rwanda.'],
                ['title' => 'Unhurried days', 'text' => 'Itineraries with breathing room — dawn game drives, quiet afternoons, long views.'],
                ['title' => 'Light footprint', 'text' => 'Lodges and operators chosen for care of community and wilderness.'],
            ]),
            'featured_eyebrow' => 'Featured journeys',
            'featured_heading' => 'Destinations worth the voyage',
            'featured_intro' => 'Handpicked parks and wildernesses across Uganda, Kenya, Tanzania, and Rwanda.',
            'home_cta_heading' => 'Ready when you are',
            'home_cta_text' => 'Share a few dates and ideas — we will sketch a thoughtful itinerary for your East African chapter.',
            'home_cta_button' => 'Plan your journey',
            'destinations_eyebrow' => 'East Africa',
            'destinations_heading' => 'Destinations',
            'destinations_intro' => 'From misty gorilla forests to endless savannah — choose your next chapter.',
            'destinations_note_heading' => 'How we help you choose',
            'destinations_note_body' => "Every destination on this page can stand alone or combine into a multi-country journey. Tell us what you hope to see — primates, big cats, birds, culture — and we will recommend a route that fits your dates and pace.\n\nPermits for gorilla trekking and popular lodges book out months ahead in peak season. An early conversation helps secure the right camps.",
            'destinations_cta_heading' => 'Not sure where to begin?',
            'destinations_cta_text' => 'Write to us with your travel window and interests. We will reply with a shortlist and sample nights.',
            'about_eyebrow' => 'Our story',
            'about_title' => 'About Pearl Pulse Safaris',
            'about_lead' => 'We craft intimate East African journeys for travellers who value stillness as much as spectacle.',
            'about_content' => '<p>Pearl Pulse Safaris crafts intimate journeys through Uganda, Kenya, Tanzania, and Rwanda. We favour unhurried days, expert local guides, and stays that leave a light footprint.</p><p>From gorilla trekking in Bwindi to the great migrations of the Mara and Serengeti, every itinerary is shaped around how you want to feel on the road — curious, restored, and deeply connected to place.</p><p>Our team works closely with camps, conservation projects, and community partners so that each booking supports the landscapes and people who make these journeys possible.</p>',
            'about_values' => json_encode([
                ['title' => 'Presence over checklist', 'text' => 'We leave space for the unexpected — a leopard at dusk, a conversation by the fire.'],
                ['title' => 'Trusted partnerships', 'text' => 'Long relationships with lodges and guides mean smoother logistics and warmer welcome.'],
                ['title' => 'Responsible travel', 'text' => 'We favour low-impact stays and experiences that respect wildlife and local communities.'],
            ]),
            'about_approach_heading' => 'How we work with you',
            'about_approach_body' => "It starts with a conversation: when you can travel, who you are travelling with, and what you hope the trip will feel like. From there we propose a clear outline — destinations, nights, and a sense of daily rhythm — then refine lodges and activities together.\n\nOnce confirmed, we handle permits, transfers, and briefing notes so you can arrive ready to look up, not down at logistics.",
            'about_cta_heading' => 'Let’s talk about your trip',
            'about_cta_text' => 'A short note is enough to begin. We usually reply within one business day.',
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
        $existing = Setting::getValue('hero_image');
        $source = storage_path('app/seed-downloads/hero.jpg');

        if (is_file($source)) {
            if ($existing) {
                $uploader->delete($existing);
            }

            return $uploader->storeFromPath($source, 'site');
        }

        if ($existing && Storage::disk('public')->exists($existing)) {
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
