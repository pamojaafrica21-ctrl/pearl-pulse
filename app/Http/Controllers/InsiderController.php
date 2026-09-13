<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InsiderController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->string('type')->toString();

        $articles = Article::query()
            ->published()
            ->when($type !== '' && in_array($type, Article::TYPES, true), fn ($q) => $q->where('type', $type))
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->get();

        return view('public.insiders.index', compact('articles', 'type'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->isPublished(), 404);

        $more = Article::query()
            ->published()
            ->whereKeyNot($article->id)
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('public.insiders.show', compact('article', 'more'));
    }
}
