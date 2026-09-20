<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function favorites(Request $request): View
    {
        $journeys = $request->user()
            ->favoriteJourneys()
            ->published()
            ->with('countries')
            ->orderByPivot('created_at', 'desc')
            ->get();

        $inspire = \App\Models\Journey::query()
            ->published()
            ->with('countries')
            ->orderBy('sort_order')
            ->first();

        return view('public.account.favorites', [
            'journeys' => $journeys,
            'inspireImage' => $inspire?->coverUrl() ?: app(\App\Services\SettingService::class)->heroImageUrl(),
        ]);
    }

    public function requests(Request $request): View
    {
        $enquiries = $request->user()
            ->enquiries()
            ->latest()
            ->get();

        return view('public.account.requests', [
            'enquiries' => $enquiries,
        ]);
    }

    public function profile(): View
    {
        return view('public.account.profile');
    }
}
