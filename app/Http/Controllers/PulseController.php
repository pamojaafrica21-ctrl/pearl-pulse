<?php

namespace App\Http\Controllers;

use App\Models\PulseItem;
use Illuminate\View\View;

class PulseController extends Controller
{
    public function __invoke(): View
    {
        $items = PulseItem::query()->visible()->orderBy('sort_order')->get();

        return view('public.true-pulse.index', compact('items'));
    }
}
