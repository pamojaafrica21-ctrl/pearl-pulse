<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PulseController extends Controller
{
    public function __invoke(): View
    {
        return view('public.true-pulse.index');
    }
}
