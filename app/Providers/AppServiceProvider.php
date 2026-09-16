<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class);
    }

    public function boot(): void
    {
        config([
            'livewire.temporary_file_upload.rules' => ['required', 'file', 'max:102400'],
        ]);

        View::composer('layouts.public', function ($view) {
            try {
                $settings = app(SettingService::class);
                $heroImage = $settings->heroImageUrl();
                $team = \App\Models\TeamMember::query()->published()->orderBy('sort_order')->first();
                $stay = \App\Models\Stay::query()->published()->orderBy('sort_order')->first();
                $pulse = \App\Models\PulseItem::query()->published()->orderBy('sort_order')->first();
                $article = \App\Models\Article::query()->published()->orderBy('sort_order')->first();

                $view->with([
                    'siteContact' => $settings->contact(),
                    'siteSocial' => $settings->social(),
                    'reviewLinks' => $settings->reviewLinks(),
                    'footerBlurb' => $settings->get('footer_blurb', 'Private journeys through East Africa.'),
                    'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
                    'navCountries' => \App\Models\Country::query()->published()->orderBy('sort_order')->get(),
                    'navExperiences' => \App\Models\Experience::query()->published()->orderBy('sort_order')->get(),
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
                            'image' => $team?->coverUrl() ?: $heroImage,
                        ],
                        [
                            'label' => 'Travel with a reason',
                            'href' => route('travel-with-a-reason'),
                            'teaser' => 'Conservation, communities, and local people.',
                            'image' => $pulse?->coverUrl() ?: $heroImage,
                        ],
                        [
                            'label' => 'Selected stays',
                            'href' => route('stays.index'),
                            'teaser' => 'Lodges we choose. We do not own them.',
                            'image' => $stay?->coverUrl() ?: $heroImage,
                            'muted' => true,
                        ],
                        [
                            'label' => 'True Pulse',
                            'href' => route('true-pulse'),
                            'teaser' => 'Guest photographs and stories we have approved.',
                            'image' => $pulse?->coverUrl() ?: $heroImage,
                            'muted' => true,
                        ],
                        [
                            'label' => 'Insiders',
                            'href' => route('insiders.index'),
                            'teaser' => 'Guides from the ground — permits, seasons, packing.',
                            'image' => $article?->coverUrl() ?: $heroImage,
                            'muted' => true,
                        ],
                    ],
                ]);
            } catch (\Throwable) {
                $view->with([
                    'siteContact' => ['address' => '', 'phone' => '', 'email' => '', 'admin_email' => '', 'whatsapp' => ''],
                    'siteSocial' => ['instagram' => '', 'facebook' => '', 'twitter' => ''],
                    'reviewLinks' => ['google' => '', 'tripadvisor' => ''],
                    'footerBlurb' => 'Private journeys through East Africa.',
                    'whatsappUrl' => null,
                    'navCountries' => collect(),
                    'navExperiences' => collect(),
                    'navAboutItems' => [],
                ]);
            }
        });
    }
}
