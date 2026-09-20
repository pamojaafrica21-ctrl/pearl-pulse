<?php

namespace App\Providers;

use App\Services\SettingService;
use App\Support\PublicNav;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingService::class);
        $this->app->singleton(\App\Services\ImageUploader::class);
    }

    public function boot(): void
    {
        config([
            'livewire.temporary_file_upload.rules' => ['required', 'file', 'max:102400'],
        ]);

        View::composer('layouts.public', function ($view) {
            try {
                $view->with(PublicNav::forLayout(app(SettingService::class)));
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

        foreach ([
            \App\Models\Country::class,
            \App\Models\Experience::class,
            \App\Models\TeamMember::class,
            \App\Models\Stay::class,
            \App\Models\PulseItem::class,
            \App\Models\Article::class,
            \App\Models\Destination::class,
            \App\Models\Journey::class,
        ] as $model) {
            $model::saved(fn () => PublicNav::forget());
            $model::deleted(fn () => PublicNav::forget());
        }
    }
}
