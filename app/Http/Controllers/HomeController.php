<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Experience;
use App\Models\Journey;
use App\Models\Review;
use App\Services\SettingService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SettingService $settings): View
    {
        $countries = Country::query()
            ->published()
            ->with(['journeys' => fn ($q) => $q->published()->orderBy('journeys.sort_order')])
            ->orderBy('sort_order')
            ->get();

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
            'heroSlides' => $settings->heroSlides(),
            'homeIntroEyebrow' => $settings->get('home_intro_eyebrow', 'Africa, deeply personal'),
            'homeIntroHeading' => $settings->get('home_intro_heading', ''),
            'homeIntroBody' => $settings->get('home_intro_body', ''),
            'homePillars' => $settings->listItems('home_pillars', 6),
            'destinationsEyebrow' => $settings->get('home_destinations_eyebrow', 'Destinations'),
            'destinationsHeading' => $settings->get('home_destinations_heading', 'Where do you want to go?'),
            'destinationsIntro' => $settings->get('home_destinations_intro', 'Uganda is home. Rwanda, Kenya, and Tanzania complete the map — parks, seasons, and journeys shaped around how you want to travel.'),
            'experiencesEyebrow' => $settings->get('home_experiences_eyebrow', 'Experiences'),
            'experiencesHeading' => $settings->get('home_experiences_heading', 'What kind of trip are you looking for?'),
            'experiencesIntro' => $settings->get('home_experiences_intro', 'Wildlife spectacles, wilderness retreats, cultural immersions, birding and photography — each with its own pace and places.'),
            'featuredEyebrow' => $settings->get('featured_eyebrow', 'Journeys worth taking'),
            'featuredHeading' => $settings->get('featured_heading', 'Signature journeys'),
            'featuredIntro' => $settings->get('featured_intro', ''),
            'homeCtaHeading' => $settings->get('home_cta_heading', 'Where will Africa take you?'),
            'homeCtaText' => $settings->get('home_cta_text', ''),
            'homeCtaButton' => $settings->get('home_cta_button', 'Plan your journey'),
            'countries' => $countries,
            'journeys' => $journeys,
            'experiences' => Experience::query()->published()->orderBy('sort_order')->take(12)->get(),
            'reviews' => Review::query()->published()->with('journey')->orderBy('sort_order')->take(4)->get(),
            'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
            'reviewLinks' => $settings->reviewLinks(),
        ]);
    }
}
