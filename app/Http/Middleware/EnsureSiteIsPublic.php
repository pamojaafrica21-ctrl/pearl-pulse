<?php

namespace App\Http\Middleware;

use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteIsPublic
{
    public function __construct(protected SettingService $settings) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->settings->isSitePublic()) {
            return $next($request);
        }

        return response()->view('public.maintenance', [
            'message' => $this->settings->get('maintenance_message', 'We are preparing something special. Please check back soon.'),
        ], 503);
    }
}
