<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\PulseItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $countries = Country::query()->published()->orderBy('sort_order')->get();

        return view('public.destinations.index', compact('countries'));
    }

    public function country(string $country): View|RedirectResponse
    {
        $hub = Country::query()->where('slug', $country)->first();

        if (! $hub) {
            $park = Destination::query()->where('slug', $country)->published()->with('country')->firstOrFail();

            return redirect()->route('destinations.show', [$park->country->slug, $park->slug], 301);
        }

        abort_unless($hub->isPublished(), 404);

        $hub->load([
            'destinations' => fn ($q) => $q->published()->orderBy('sort_order'),
            'journeys' => fn ($q) => $q->published()->with('countries')->orderBy('sort_order')->limit(4),
        ]);

        $faqs = Faq::query()
            ->published()
            ->where(function ($q) use ($hub) {
                $q->where('group', 'site')
                    ->orWhere(function ($inner) use ($hub) {
                        $inner->where('faqable_type', Country::class)->where('faqable_id', $hub->id);
                    });
            })
            ->orderBy('sort_order')
            ->get();

        $pulse = PulseItem::query()
            ->visible()
            ->whereHas('destinations', fn ($q) => $q->where('country_id', $hub->id))
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('public.destinations.country', [
            'country' => $hub,
            'faqs' => $faqs,
            'pulse' => $pulse,
        ]);
    }

    public function show(Country $country, Destination $destination): View
    {
        abort_unless($country->isPublished() && $destination->isPublished(), 404);
        abort_unless($destination->country_id === $country->id, 404);

        $destination->load([
            'country',
            'images',
            'experiences' => fn ($q) => $q->published(),
            'journeys' => fn ($q) => $q->published()->with('countries'),
            'stays' => fn ($q) => $q->published(),
            'primaryStays' => fn ($q) => $q->published(),
        ]);

        return view('public.destinations.show', compact('country', 'destination'));
    }
}
