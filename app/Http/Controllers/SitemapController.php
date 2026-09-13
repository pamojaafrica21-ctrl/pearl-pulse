<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Country;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Journey;
use App\Models\Page;
use App\Models\Stay;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $content = view('public.sitemap', [
            'countries' => Country::query()->published()->orderBy('updated_at', 'desc')->get(),
            'destinations' => Destination::query()->published()->with('country')->orderBy('updated_at', 'desc')->get(),
            'journeys' => Journey::query()->published()->orderBy('updated_at', 'desc')->get(),
            'experiences' => Experience::query()->published()->orderBy('updated_at', 'desc')->get(),
            'articles' => Article::query()->published()->orderBy('updated_at', 'desc')->get(),
            'stays' => Stay::query()->published()->orderBy('updated_at', 'desc')->get(),
            'pages' => Page::query()->published()->get(),
        ])->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
