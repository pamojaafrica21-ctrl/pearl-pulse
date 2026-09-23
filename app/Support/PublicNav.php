<?php

namespace App\Support;

use App\Models\Article;
use App\Models\Country;
use App\Models\Experience;
use App\Models\PulseItem;
use App\Models\Stay;
use App\Models\TeamMember;
use App\Services\ImageUploader;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;

class PublicNav
{
    public const CACHE_KEY = 'public_nav_shell_v7';

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('public_nav_shell_v6');
        Cache::forget('public_nav_shell_v5');
        Cache::forget('public_nav_shell_v4');
        Cache::forget('public_nav_shell_v3');
        Cache::forget('public_nav_shell_v2');
        Cache::forget('public_nav_shell_v1');
    }

    /**
     * @return array<string, mixed>
     */
    public static function forLayout(SettingService $settings): array
    {
        $payload = Cache::remember(self::CACHE_KEY, 600, function () use ($settings) {
            $images = app(ImageUploader::class);
            $heroImage = $settings->heroImageUrl();
            $team = TeamMember::query()->published()->orderBy('sort_order')->first();
            $stay = Stay::query()->published()->orderBy('sort_order')->first();
            $pulse = PulseItem::query()->published()->orderBy('sort_order')->first();
            $article = Article::query()->published()->orderBy('sort_order')->first();

            $countries = Country::query()
                ->published()
                ->orderBy('sort_order')
                ->with([
                    'destinations' => fn ($q) => $q->published()->orderBy('sort_order')->orderBy('name'),
                    'journeys' => fn ($q) => $q->published()->orderBy('journeys.sort_order'),
                ])
                ->get()
                ->map(function (Country $country) use ($images) {
                    return [
                        'id' => $country->id,
                        'name' => $country->name,
                        'slug' => $country->slug,
                        'subtitle' => $country->subtitle,
                        'teaser' => $country->teaser,
                        'image' => $images->thumbUrl($country->cover_path) ?: $images->url($country->cover_path),
                        'image_full' => $images->url($country->cover_path),
                        'destinations_url' => route('destinations.country', $country),
                        'journeys_url' => route('journeys.country', $country),
                        'destinations' => $country->destinations->take(10)->map(fn ($destination) => [
                            'id' => $destination->id,
                            'name' => $destination->name,
                            'teaser' => $destination->teaser,
                            'subtitle' => $destination->subtitle,
                            'url' => route('destinations.show', [$country, $destination]),
                            'image' => $images->thumbUrl($destination->cover_path) ?: $images->url($destination->cover_path),
                            'image_full' => $images->url($destination->cover_path),
                        ])->values()->all(),
                        'journeys' => $country->journeys->take(8)->map(fn ($journey) => [
                            'id' => $journey->id,
                            'name' => $journey->name,
                            'slug' => $journey->slug,
                            'teaser' => $journey->teaser,
                            'duration_label' => $journey->duration_label,
                            'image' => $images->thumbUrl($journey->cover_path) ?: $images->url($journey->cover_path),
                            'url' => route('journeys.show', $journey),
                        ])->values()->all(),
                    ];
                })
                ->values()
                ->all();

            $experiences = Experience::query()
                ->published()
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Experience $experience) => [
                    'id' => $experience->id,
                    'name' => $experience->name,
                    'slug' => $experience->slug,
                    'subtitle' => $experience->subtitle,
                    'teaser' => $experience->teaser,
                    'image' => $images->thumbUrl($experience->cover_path) ?: $images->url($experience->cover_path),
                    'image_full' => $images->url($experience->cover_path),
                    'url' => route('experiences.show', $experience),
                ])
                ->values()
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

        $payload['navCountries'] = collect($payload['navCountries'] ?? []);
        $payload['navExperiences'] = collect($payload['navExperiences'] ?? []);
        $payload['navAboutItems'] = $payload['navAboutItems'] ?? [];

        return $payload;
    }
}
