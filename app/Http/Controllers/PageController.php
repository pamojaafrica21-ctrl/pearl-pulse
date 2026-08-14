<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(SettingService $settings): View
    {
        return view('public.about', [
            'eyebrow' => $settings->get('about_eyebrow', 'Our story'),
            'title' => $settings->get('about_title', 'About Pearl Pulse Safaris'),
            'lead' => $settings->get('about_lead', ''),
            'content' => $settings->get('about_content', ''),
            'values' => $settings->listItems('about_values'),
            'approachHeading' => $settings->get('about_approach_heading', ''),
            'approachBody' => $settings->get('about_approach_body', ''),
            'ctaHeading' => $settings->get('about_cta_heading', ''),
            'ctaText' => $settings->get('about_cta_text', ''),
        ]);
    }

    public function contact(SettingService $settings): View
    {
        return view('public.contact', [
            'contact' => $settings->contact(),
        ]);
    }
}
