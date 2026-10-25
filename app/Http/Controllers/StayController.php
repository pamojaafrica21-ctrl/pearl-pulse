<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Stay;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StayController extends Controller
{
    public function index(Request $request): View
    {
        $countrySlug = $request->string('country')->toString();

        $stays = Stay::query()
            ->published()
            ->with('destination.country')
            ->when($countrySlug !== '', function ($query) use ($countrySlug) {
                $query->whereHas('destination.country', fn ($q) => $q->where('slug', $countrySlug));
            })
            ->orderBy('sort_order')
            ->get();

        $country = $countrySlug !== ''
            ? Country::query()->published()->where('slug', $countrySlug)->first()
            : null;

        return view('public.stays.index', compact('stays', 'country'));
    }

    public function show(Stay $stay): View
    {
        abort_unless($stay->isPublished(), 404);

        $stay->load(['destination.country', 'journeys' => fn ($q) => $q->published()->with('countries'), 'images']);

        return view('public.stays.show', compact('stay'));
    }
}
