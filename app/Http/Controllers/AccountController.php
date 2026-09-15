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

        return view('public.account.favorites', [
            'journeys' => $journeys,
        ]);
    }
}
