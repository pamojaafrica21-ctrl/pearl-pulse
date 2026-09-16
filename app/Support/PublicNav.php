<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Country;
use App\Models\Experience;
use App\Models\PulseItem;
use App\Models\Stay;
use App\Models\TeamMember;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;

class PublicNav
{
    public const CACHE_KEY = 'public_nav_shell_v2';

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('public_nav_shell_v1');
    }

    /**
     * @return array<string, mixed>
     */
    public static function forLayout(SettingService $settings): array
    {
        return Cache::remember(self::CACHE_KEY, 600, function () use ($settings) {
            $heroImage = $settings->heroImageUrl();
            $team = TeamMember::query()->published()->orderBy('sort_order')->first();
            $stay = Stay::query()->published()->orderBy('sort_order')->first();
            $pulse = PulseItem::query()->published()->orderBy('sort_order')->first();
            $article = Article::query()->published()->orderBy('sort_order')->first();

            $countries = Country::query()
                ->published()
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'subtitle', 'teaser', 'cover_path', 'sort_order', 'status'])
                ->map(fn (Country $country) => [
                    'name' => $country->name,
                    'slug' => $country->slug,
                    'subtitle' => $country->subtitle,
                    'teaser' => $country->teaser,
                    'image' => $country->coverThumbUrl() ?: $country->coverUrl(),
                    'journeys_url' => route('journeys.country', $country),
                    'destinations_url' => route('destinations.country', $country),
                ])
                ->all();

            $experiences = Experience::query()
                ->published()
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'teaser', 'cover_path', 'sort_order', 'status'])
                ->map(fn (Experience $experience) => [
                    'name' => $experience->name,
                    'slug' => $experience->slug,
                    'teaser' => $experience->teaser,
                    'image' => $experience->coverThumbUrl() ?: $experience->coverUrl(),
                    'url' => route('experiences.show', $experience),
                ])
                ->all();

            return [
                'siteContact' => $settings->contact(),
                'siteSocial' => $settings->social(),
                'reviewLinks' => $settings->reviewLinks(),
                'footerBlurb' => $settings->get('footer_blurb', 'Private journeys through East Africa.'),
                'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
                'navCountries' => $countries,
                'navExperiences' => $experiences,
                'navAboutItems' => [
                    [
                        'label' => 'Our story',
                        'href' => route('about'),
                        'teaser' => 'Who we are and how we plan private journeys.',
                        'image' => $heroImage,
                    ],
                    [
                        'label' => 'Our people',
                        'href' => route('our-people'),
                        'teaser' => 'Guides and planners who live this work.',
                        'image' => $team?->coverThumbUrl() ?: ($team?->coverUrl() ?: $heroImage),
                    ],
                    [
                        'label' => 'Travel with a reason',
                        'href' => route('travel-with-a-reason'),
                        'teaser' => 'Conservation, communities, and local people.',
                        'image' => $pulse?->coverThumbUrl() ?: ($pulse?->coverUrl() ?: $heroImage),
                    ],
                    [
                        'label' => 'Selected stays',
                        'href' => route('stays.index'),
                        'teaser' => 'Lodges we choose. We do not own them.',
                        'image' => $stay?->coverThumbUrl() ?: ($stay?->coverUrl() ?: $heroImage),
                        'muted' => true,
                    ],
                    [
                        'label' => 'True Pulse',
                        'href' => route('true-pulse'),
                        'teaser' => 'Guest photographs and stories we have approved.',
                        'image' => $pulse?->coverThumbUrl() ?: ($pulse?->coverUrl() ?: $heroImage),
                        'muted' => true,
                    ],
                    [
                        'label' => 'Insiders',
                        'href' => route('insiders.index'),
                        'teaser' => 'Guides from the ground — permits, seasons, packing.',
                        'image' => $article?->coverThumbUrl() ?: ($article?->coverUrl() ?: $heroImage),
                        'muted' => true,
                    ],
                ],
            ];
        });
    }
}
