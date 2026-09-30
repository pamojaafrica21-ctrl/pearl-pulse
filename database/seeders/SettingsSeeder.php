<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $heroPath = $this->storeHero();

        $defaults = [
            'hero_headline' => "Private journeys into Africa’s wild heart",
            'hero_tagline' => 'Private, tailor-made journeys shaped around how you want to experience Africa.',
            'hero_kicker' => 'Uganda · Rwanda · Kenya · Tanzania',
            'hero_image' => $heroPath,
            'hero_video_url' => '',
            'home_intro_eyebrow' => 'Africa, deeply personal',
            'home_intro_heading' => 'Local African specialists who design private journeys',
            'home_intro_body' => 'Pearl Pulse is a Uganda-based safari company. We design private journeys across Uganda, Rwanda, Kenya, and Tanzania — shaped around how you want to experience Africa, not a fixed catalogue.',
            'home_trust_items' => json_encode([
                ['title' => 'Gorilla permits secured', 'text' => ''],
                ['title' => 'Private vehicles only', 'text' => ''],
                ['title' => 'Local expert guides', 'text' => ''],
                ['title' => 'Uganda-based', 'text' => ''],
            ]),
            'home_pillars' => json_encode([
                ['title' => 'Private by design', 'text' => 'Your vehicle, your pace, your group — never shared seat sales.'],
                ['title' => 'Local knowledge', 'text' => 'Uganda-based specialists with deep East Africa relationships.'],
                ['title' => 'Seamless logistics', 'text' => 'Permits, lodges, transfers, and timing handled end to end.'],
                ['title' => 'Conservation-minded', 'text' => 'Travel that supports communities and wild places.'],
                ['title' => 'Honest counsel', 'text' => 'We say no when a trip does not fit — and reshape when it can.'],
            ]),
            'home_specialists_eyebrow' => 'Specialist journeys',
            'home_specialists_heading' => 'Travel shaped around how you want to feel',
            'home_specialists_intro' => 'Family, honeymoon, photography, and wellness — private chapters designed with intent.',
            'home_destinations_heading' => 'Where do you want to go?',
            'home_experiences_heading' => 'What kind of trip are you looking for?',
            'featured_eyebrow' => 'Signature journeys',
            'featured_heading' => 'Sample itineraries to begin from',
            'featured_intro' => 'Three or four starting points. Open a journey for the full day-by-day — or ask us to rewrite it around you.',
            'home_cta_heading' => 'Not sure where to begin?',
            'home_cta_text' => 'Use the Journey Finder, write to us, or message on WhatsApp — we will meet you where you are.',
            'home_cta_button' => 'Plan your journey',
            'contact_whatsapp' => '+256700000000',
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
            'footer_blurb' => 'A local African safari company with world-class presentation. Private journeys across Uganda, Rwanda, Kenya, and Tanzania.',
            'site_public' => '1',
            'maintenance_message' => 'We are preparing something special. Please check back soon.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        Setting::flushCache();
    }

    protected function storeHero(): string
    {
        return SeedImage::photo('hero', 'site/hero-safari.jpg', 2000, 1200);
    }
}
