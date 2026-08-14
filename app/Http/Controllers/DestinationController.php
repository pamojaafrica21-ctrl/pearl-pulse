<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Services\SettingService;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(SettingService $settings): View
    {
        return view('public.destinations.index', [
            'eyebrow' => $settings->get('destinations_eyebrow', 'East Africa'),
            'heading' => $settings->get('destinations_heading', 'Destinations'),
            'intro' => $settings->get('destinations_intro', 'From misty gorilla forests to endless savannah — choose your next chapter.'),
            'noteHeading' => $settings->get('destinations_note_heading', ''),
            'noteBody' => $settings->get('destinations_note_body', ''),
            'ctaHeading' => $settings->get('destinations_cta_heading', ''),
            'ctaText' => $settings->get('destinations_cta_text', ''),
        ]);
    }

    public function show(Destination $destination): View
    {
        abort_unless($destination->isPublished(), 404);

        $destination->load('images');

        return view('public.destinations.show', [
            'destination' => $destination,
        ]);
    }
}
