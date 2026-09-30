<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('public.faqs.index', [
            'faqs' => Faq::query()
                ->published()
                ->where('group', 'site')
                ->whereNull('faqable_id')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
