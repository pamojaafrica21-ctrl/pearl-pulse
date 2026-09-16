<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use App\Services\SettingService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function __construct(protected SettingService $settings) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || ! $response->isSuccessful()) {
            return $response;
        }

        if (! $this->settings->isSitePublic()) {
            return $response;
        }

        if ($this->shouldSkip($request)) {
            return $response;
        }

        $sessionId = $request->session()->getId();
        if ($sessionId) {
            $path = $request->path();
            defer(static fn () => PageVisit::record($path, $sessionId));
        }

        return $response;
    }

    protected function shouldSkip(Request $request): bool
    {
        if ($request->is(
            'admin',
            'admin/*',
            'login',
            'logout',
            'forgot-password',
            'reset-password/*',
            'verify-email',
            'verify-email/*',
            'confirm-password',
            'livewire/*',
            'up',
            'build/*',
            'storage/*',
        )) {
            return true;
        }

        $userAgent = strtolower((string) $request->userAgent());

        return str_contains($userAgent, 'bot')
            || str_contains($userAgent, 'crawl')
            || str_contains($userAgent, 'spider');
    }
}
