<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\SettingService;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(SettingService $settings): View
    {
        return view('public.reviews.index', [
            'reviews' => Review::query()
                ->published()
                ->with('journey')
                ->orderBy('sort_order')
                ->get(),
            'reviewLinks' => $settings->reviewLinks(),
        ]);
    }
}
