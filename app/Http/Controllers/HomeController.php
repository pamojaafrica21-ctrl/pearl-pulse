<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Experience;
use App\Models\Journey;
use App\Services\SettingService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SettingService $settings): View
    {
        $journeys = Journey::query()
            ->published()
            ->signature()
            ->with('countries')
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        if ($journeys->isEmpty()) {
            $journeys = Journey::query()->published()->with('countries')->orderBy('sort_order')->take(3)->get();
        }

        return view('public.home', [
            'heroHeadline' => $settings->get('hero_headline', 'Private journeys into Africa’s wild heart'),
            'heroTagline' => $settings->get('hero_tagline', 'Private, tailor-made journeys shaped around how you want to experience Africa.'),
            'heroKicker' => $settings->get('hero_kicker', 'Uganda · Rwanda · Kenya · Tanzania'),
            'heroImageUrl' => $settings->heroImageUrl(),
            'heroVideoUrl' => $settings->get('hero_video_url'),
            'homeIntroEyebrow' => $settings->get('home_intro_eyebrow', 'Africa, deeply personal'),
            'homeIntroHeading' => $settings->get('home_intro_heading', ''),
            'homeIntroBody' => $settings->get('home_intro_body', ''),
            'homePillars' => $settings->listItems('home_pillars', 6),
            'featuredEyebrow' => $settings->get('featured_eyebrow', 'Journeys worth taking'),
            'featuredHeading' => $settings->get('featured_heading', 'Signature journeys'),
            'featuredIntro' => $settings->get('featured_intro', ''),
            'homeCtaHeading' => $settings->get('home_cta_heading', 'Where will Africa take you?'),
            'homeCtaText' => $settings->get('home_cta_text', ''),
            'homeCtaButton' => $settings->get('home_cta_button', 'Plan your journey'),
            'countries' => Country::query()->published()->orderBy('sort_order')->get(),
            'journeys' => $journeys,
            'experiences' => Experience::query()->published()->orderBy('sort_order')->take(4)->get(),
            'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
        ]);
    }
}
