<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Journey;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JourneyController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        $journeys = Journey::query()
            ->published()
            ->with('countries')
            ->when($type === 'signature', fn ($q) => $q->where('is_signature', true))
            ->when($type === 'multi', fn ($q) => $q->where('is_multi_country', true))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('public.journeys.index', [
            'journeys' => $journeys,
            'countries' => Country::query()->published()->orderBy('sort_order')->get(),
            'type' => $type,
            'heading' => match ($type) {
                'signature' => 'Signature journeys',
                'multi' => 'Multi-country journeys',
                default => 'All journeys',
            },
        ]);
    }

    public function finder(): View
    {
        return view('public.journeys.finder');
    }

    public function country(Country $country): View
    {
        abort_unless($country->isPublished(), 404);

        $journeys = $country->journeys()->published()->with('countries')->orderBy('sort_order')->get();

        return view('public.journeys.country', [
            'country' => $country,
            'journeys' => $journeys,
        ]);
    }

    public function show(Journey $journey): View
    {
        abort_unless($journey->isPublished(), 404);

        $journey->load(['countries', 'destinations.country', 'experiences', 'stays', 'reviews' => fn ($q) => $q->published(), 'images']);

        $related = Journey::query()
            ->published()
            ->whereKeyNot($journey->id)
            ->with('countries')
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('public.journeys.show', [
            'journey' => $journey,
            'related' => $related,
        ]);
    }
}
