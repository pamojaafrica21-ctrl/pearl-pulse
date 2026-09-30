<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::query()
            ->published()
            ->where('group', 'site')
            ->whereNull('faqable_id')
            ->orderBy('sort_order')
            ->get();

        $topicOrder = array_keys(Faq::TOPICS);
        $grouped = $faqs
            ->groupBy(fn (Faq $faq) => $faq->topic ?: 'general')
            ->sortBy(fn ($_, $key) => array_search($key, $topicOrder, true) === false
                ? 999
                : array_search($key, $topicOrder, true));

        return view('public.faqs.index', [
            'grouped' => $grouped,
            'topicLabels' => Faq::TOPICS + ['general' => 'General'],
        ]);
    }
}
