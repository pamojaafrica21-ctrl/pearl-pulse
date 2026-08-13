<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(SettingService $settings): View
    {
        return view('public.about', [
            'content' => $settings->get('about_content', ''),
            'title' => $settings->get('about_title', 'About Pearl Pulse Safaris'),
        ]);
    }

    public function contact(SettingService $settings): View
    {
        return view('public.contact', [
            'contact' => $settings->contact(),
        ]);
    }
}
