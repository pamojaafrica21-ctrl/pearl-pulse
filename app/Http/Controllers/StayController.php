<?php

namespace App\Http\Controllers;

use App\Models\Stay;
use Illuminate\View\View;

class StayController extends Controller
{
    public function index(): View
    {
        $stays = Stay::query()->published()->with('destination.country')->orderBy('sort_order')->get();

        return view('public.stays.index', compact('stays'));
    }

    public function show(Stay $stay): View
    {
        abort_unless($stay->isPublished(), 404);

        $stay->load(['destination.country', 'journeys' => fn ($q) => $q->published()->with('countries'), 'images']);

        return view('public.stays.show', compact('stay'));
    }
}
