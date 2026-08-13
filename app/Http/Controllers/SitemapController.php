<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $destinations = Destination::query()
            ->published()
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $content = view('public.sitemap', [
            'destinations' => $destinations,
        ])->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
