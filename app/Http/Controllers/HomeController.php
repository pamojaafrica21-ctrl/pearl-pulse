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
            ->with(['destinations' => fn ($q) => $q->published()->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('name')])
            ->orderBy('sort_order')
            ->get();

        $countryOrder = ['uganda', 'kenya', 'tanzania', 'rwanda'];
        $orderedCountries = collect($countryOrder)
            ->map(fn (string $slug) => $countries->firstWhere('slug', $slug))
            ->filter()
            ->concat($countries->reject(fn (Country $country) => in_array($country->slug, $countryOrder, true)))
            ->values();

        $destinationPanels = $orderedCountries
            ->map(function (Country $country) {
                $items = $country->destinations->take(3)->map(fn ($destination) => [
                    'href' => route('destinations.show', [$country, $destination]),
                    'image' => $destination->coverUrl() ?: $country->coverUrl(),
                    'thumb' => $destination->coverThumbUrl()
                        ?: $country->coverThumbUrl()
                        ?: $destination->coverUrl()
                        ?: $country->coverUrl(),
                    'kicker' => $country->name,
                    'title' => $destination->name,
                    'meta' => $destination->region ?: $destination->duration,
                    'teaser' => $destination->teaser,
                ])->values();

                if ($items->isEmpty()) {
                    return null;
                }

                return [
                    'key' => $country->slug,
                    'label' => $country->name,
                    'href' => route('destinations.country', $country),
                    'explore' => 'Explore '.$country->name,
                    'items' => $items,
                ];
            })
            ->filter()
            ->values();

        $multiJourneys = Journey::query()
            ->published()
            ->where('is_multi_country', true)
            ->with('countries')
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        if ($multiJourneys->isNotEmpty()) {
            $destinationPanels->push([
                'key' => 'multi',
                'label' => 'Around East Africa',
                'href' => route('journeys.index', ['type' => 'multi']),
                'explore' => 'Explore multi-country',
                'items' => $multiJourneys->map(fn (Journey $journey) => [
                    'href' => route('journeys.show', $journey),
                    'image' => $journey->coverUrl(),
                    'thumb' => $journey->coverThumbUrl() ?: $journey->coverUrl(),
                    'kicker' => $journey->countries->pluck('name')->filter()->join(' · ') ?: 'Multi-country',
                    'title' => $journey->name,
                    'meta' => $journey->duration_label,
                    'teaser' => $journey->teaser,
                ])->values(),
            ]);
        }

        $journeys = Journey::query()
            ->published()
            ->signature()
            ->with('countries')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        if ($journeys->isEmpty()) {
            $journeys = Journey::query()->published()->with('countries')->orderBy('sort_order')->take(6)->get();
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
            'destinationPanels' => $destinationPanels,
            'journeys' => $journeys,
            'experiences' => Experience::query()->published()->orderBy('sort_order')->take(8)->get(),
            'reviews' => Review::query()->published()->with('journey')->orderBy('sort_order')->take(4)->get(),
            'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
            'reviewLinks' => $settings->reviewLinks(),
        ]);
    }
}
