<?php

namespace App\Http\Controllers;

use App\Models\PulseItem;
use App\Services\SettingService;
use Illuminate\View\View;

class PulseController extends Controller
{
    public function __invoke(SettingService $settings): View
    {
        $items = PulseItem::query()->visible()->orderBy('sort_order')->get();

        return view('public.true-pulse.index', [
            'items' => $items,
            'instagramUrl' => $settings->social()['instagram'] ?? null,
        ]);
    }
}
