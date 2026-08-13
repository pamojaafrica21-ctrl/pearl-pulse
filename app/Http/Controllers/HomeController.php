<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Services\SettingService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SettingService $settings): View
    {
        $featured = Destination::query()
            ->published()
            ->featured()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(8)
            ->get();

        if ($featured->isEmpty()) {
            $featured = Destination::query()
                ->published()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->take(4)
                ->get();
        }

        return view('public.home', [
            'featured' => $featured,
            'heroTagline' => $settings->get('hero_tagline', 'East Africa, beautifully paced.'),
            'heroImageUrl' => $settings->heroImageUrl(),
            'heroVideoUrl' => $settings->get('hero_video_url'),
        ]);
    }
}
