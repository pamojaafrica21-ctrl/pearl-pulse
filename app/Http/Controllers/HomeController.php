<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Country;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Journey;
use App\Models\PulseItem;
use App\Models\Review;
use App\Models\Specialist;
use App\Models\Stay;
use App\Models\TeamMember;
use App\Services\SettingService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(SettingService $settings): View
    {
        $countries = Country::query()
            ->published()
            ->orderBy('sort_order')
            ->get();

        $countryOrder = ['uganda', 'kenya', 'tanzania', 'rwanda'];
        $orderedCountries = collect($countryOrder)
            ->map(fn (string $slug) => $countries->firstWhere('slug', $slug))
            ->filter()
            ->concat($countries->reject(fn (Country $country) => in_array($country->slug, $countryOrder, true)))
            ->values();

        $hasMulti = Journey::query()->published()->where('is_multi_country', true)->exists();

        $journeys = Journey::query()
            ->published()
            ->signature()
            ->with('countries')
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        if ($journeys->isEmpty()) {
            $journeys = Journey::query()->published()->with('countries')->orderBy('sort_order')->take(4)->get();
        }

        $reasonImage = $settings->get('home_reason_image')
            ?: Country::query()->published()->where('slug', 'uganda')->value('cover_path');

        $reasonImageUrl = null;
        if (is_string($reasonImage) && $reasonImage !== '') {
            $reasonImageUrl = str_starts_with($reasonImage, 'http')
                ? $reasonImage
                : asset('storage/'.$reasonImage);
        }
        if (! $reasonImageUrl) {
            $reasonImageUrl = Country::query()->published()->where('slug', 'uganda')->first()?->coverUrl()
                ?: $countries->first()?->coverUrl();
        }

        $introImage = $orderedCountries->first()?->coverThumbUrl()
            ?: $orderedCountries->first()?->coverUrl();

        $trustItems = $settings->listItems('home_trust_items', 6);
        if ($trustItems === []) {
            $trustItems = [
                ['title' => 'Gorilla permits secured'],
                ['title' => 'Private vehicles only'],
                ['title' => 'Local expert guides'],
                ['title' => 'Uganda-based'],
            ];
        }

        return view('public.home', [
            'heroSlides' => $settings->heroSlides(),
            'trustItems' => $trustItems,
            'homeIntroEyebrow' => $settings->get('home_intro_eyebrow', 'Africa, deeply personal'),
            'homeIntroHeading' => $settings->get('home_intro_heading', 'Local African specialists who design private journeys'),
            'homeIntroBody' => $settings->get('home_intro_body', "Pearl Pulse is a Uganda-based safari company. We design private journeys across Uganda, Rwanda, Kenya, and Tanzania — shaped around how you want to experience Africa, not a fixed catalogue."),
            'introImage' => $introImage,
            'homePillars' => $settings->listItems('home_pillars', 6),
            'destinationsEyebrow' => $settings->get('home_destinations_eyebrow', 'Destinations'),
            'destinationsHeading' => $settings->get('home_destinations_heading', 'Where do you want to go?'),
            'destinationsIntro' => $settings->get('home_destinations_intro', 'Uganda is home. Rwanda, Kenya, and Tanzania complete the map — parks, seasons, and journeys shaped around how you want to travel.'),
            'experiencesEyebrow' => $settings->get('home_experiences_eyebrow', 'Experiences'),
            'experiencesHeading' => $settings->get('home_experiences_heading', 'What kind of trip are you looking for?'),
            'experiencesIntro' => $settings->get('home_experiences_intro', 'Wildlife spectacles, wilderness retreats, cultural immersions, birding and photography — each with its own pace and places.'),
            'specialistsEyebrow' => $settings->get('home_specialists_eyebrow', 'Specialist journeys'),
            'specialistsHeading' => $settings->get('home_specialists_heading', 'Travel shaped around how you want to feel'),
            'specialistsIntro' => $settings->get('home_specialists_intro', 'Family, honeymoon, photography, and wellness — private chapters designed with intent.'),
            'featuredEyebrow' => $settings->get('featured_eyebrow', 'Signature journeys'),
            'featuredHeading' => $settings->get('featured_heading', 'Sample itineraries to begin from'),
            'featuredIntro' => $settings->get('featured_intro', 'Three or four starting points. Open a journey for the full day-by-day — or ask us to rewrite it around you.'),
            'homeCtaHeading' => $settings->get('home_cta_heading', 'Not sure where to begin?'),
            'homeCtaText' => $settings->get('home_cta_text', 'Use the Journey Finder, write to us, or message on WhatsApp — we will meet you where you are.'),
            'homeCtaButton' => $settings->get('home_cta_button', 'Plan your journey'),
            'countries' => $orderedCountries,
            'hasMultiCountry' => $hasMulti,
            'journeys' => $journeys,
            'experiences' => Experience::query()->published()->orderBy('sort_order')->get(),
            'specialists' => Specialist::query()->published()->orderBy('sort_order')->get(),
            'team' => TeamMember::query()->published()->orderBy('sort_order')->take(4)->get(),
            'pulseItems' => PulseItem::query()->visible()->orderBy('sort_order')->take(4)->get(),
            'stays' => Stay::query()->published()->orderBy('sort_order')->take(3)->get(),
            'fieldNotes' => Article::query()->published()->orderByDesc('is_featured')->orderBy('sort_order')->take(3)->get(),
            'reviews' => Review::query()->published()->with('journey')->orderBy('sort_order')->take(3)->get(),
            'faqs' => Faq::query()
                ->published()
                ->where('group', 'site')
                ->whereNull('faqable_id')
                ->orderBy('sort_order')
                ->take(5)
                ->get(),
            'reasonImage' => $reasonImageUrl,
            'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
            'reviewLinks' => $settings->reviewLinks(),
        ]);
    }
}
