<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Review;
use App\Models\TeamMember;
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
            'team' => TeamMember::query()->published()->orderBy('sort_order')->take(3)->get(),
            'reviews' => Review::query()->published()->orderBy('sort_order')->take(3)->get(),
        ]);
    }

    public function people(): View
    {
        $team = TeamMember::query()->published()->orderBy('sort_order')->get();

        return view('public.about-people', compact('team'));
    }

    public function reason(): View
    {
        return view('public.about-reason');
    }

    public function plan(SettingService $settings): View
    {
        return view('public.plan', [
            'whatsappUrl' => $settings->whatsappUrl('Hello Pearl Pulse — I would like to plan a journey.'),
        ]);
    }

    public function legal(string $page): View
    {
        $page = Page::query()->where('slug', $page)->published()->firstOrFail();

        return view('public.legal', compact('page'));
    }
}
