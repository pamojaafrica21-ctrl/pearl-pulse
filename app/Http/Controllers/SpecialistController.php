<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\View\View;

class SpecialistController extends Controller
{
    public function index(): View
    {
        return view('public.specialists.index', [
            'specialists' => Specialist::query()->published()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Specialist $specialist): View
    {
        abort_unless($specialist->isPublished(), 404);

        return view('public.specialists.show', [
            'specialist' => $specialist,
            'others' => Specialist::query()
                ->published()
                ->where('id', '!=', $specialist->id)
                ->orderBy('sort_order')
                ->take(3)
                ->get(),
        ]);
    }
}
