<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        $experiences = Experience::query()->published()->orderBy('sort_order')->get();

        return view('public.experiences.index', compact('experiences'));
    }

    public function show(Experience $experience): View
    {
        abort_unless($experience->isPublished(), 404);

        $experience->load([
            'journeys' => fn ($q) => $q->published()->with('countries'),
            'destinations' => fn ($q) => $q->published()->with('country'),
            'faqs' => fn ($q) => $q->published(),
        ]);

        return view('public.experiences.show', compact('experience'));
    }
}
