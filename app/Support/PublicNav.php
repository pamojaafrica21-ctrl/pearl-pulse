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
    public const CACHE_KEY = 'public_nav_shell_v4';

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('public_nav_shell_v3');
        Cache::forget('public_nav_shell_v2');
        Cache::forget('public_nav_shell_v1');
    }

    /**
     * @return array<string, mixed>
     */
    public static function forLayout(SettingService $settings): array
    {
        // Never cache Eloquent collections — serialize/unserialize breaks them.
        $shell = Cache::remember(self::CACHE_KEY, 600, function () use ($settings) {
            $heroImage = $settings->heroImageUrl();
            $team = TeamMember::query()->published()->orderBy('sort_order')->first();
            $stay = Stay::query()->published()->orderBy('sort_order')->first();
            $pulse = PulseItem::query()->published()->orderBy('sort_order')->first();
            $article = Article::query()->published()->orderBy('sort_order')->first();

            return [
                'siteContact' => $settings->contact(),
                'siteSocial' => $settings->social(),
                'reviewLinks' => $settings->reviewLinks(),
                'footerBlurb' => $settings->get('footer_blurb', 'Private journeys through East Africa.'),
                'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
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

        return array_merge($shell, [
            'navCountries' => Country::query()
                ->published()
                ->orderBy('sort_order')
                ->with([
                    'destinations' => fn ($q) => $q->published()->orderBy('sort_order')->orderBy('name'),
                    'journeys' => fn ($q) => $q->published()->orderBy('journeys.sort_order'),
                ])
                ->get(),
            'navExperiences' => Experience::query()->published()->orderBy('sort_order')->get(),
        ]);
    }
}
