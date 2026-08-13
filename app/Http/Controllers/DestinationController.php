<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        return view('public.destinations.index');
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
