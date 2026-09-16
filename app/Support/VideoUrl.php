<?php

namespace App\Support;

class VideoUrl
{
    /**
     * Extract a YouTube video ID from common URL shapes, or null if not YouTube.
     */
    public static function youtubeId(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if (! is_string($host)) {
            return null;
        }

        $host = strtolower(preg_replace('/^www\./', '', $host) ?? $host);
        if (! in_array($host, ['youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'youtu.be'], true)) {
            return null;
        }

        if ($host === 'youtu.be') {
            $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
            $id = explode('/', $path)[0] ?? '';

            return self::validId($id);
        }

        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $segments = $path !== '' ? explode('/', $path) : [];

        if (($segments[0] ?? '') === 'embed' || ($segments[0] ?? '') === 'shorts' || ($segments[0] ?? '') === 'live') {
            return self::validId($segments[1] ?? '');
        }

        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        if (! empty($query['v'])) {
            return self::validId((string) $query['v']);
        }

        return null;
    }

    public static function isYoutube(?string $url): bool
    {
        return self::youtubeId($url) !== null;
    }

    /**
     * Build a muted, looping, chrome-less YouTube embed URL for background playback.
     */
    public static function youtubeEmbedUrl(?string $url, bool $autoplay = true): ?string
    {
        $id = self::youtubeId($url);
        if ($id === null) {
            return null;
        }

        $params = [
            'autoplay' => $autoplay ? 1 : 0,
            'mute' => 1,
            'controls' => 0,
            'loop' => 1,
            'playlist' => $id,
            'playsinline' => 1,
            'rel' => 0,
            'modestbranding' => 1,
            'iv_load_policy' => 3,
            'disablekb' => 1,
            'fs' => 0,
            'enablejsapi' => 1,
        ];

        $origin = '';
        if (app()->runningInConsole() === false && request()) {
            $origin = request()->getSchemeAndHttpHost();
        }
        if ($origin === '') {
            $origin = rtrim((string) config('app.url'), '/');
        }
        if ($origin !== '' && str_starts_with($origin, 'http')) {
            $params['origin'] = $origin;
        }

        return 'https://www.youtube.com/embed/'.$id.'?'.http_build_query($params);
    }

    /**
     * Best-effort poster image for a YouTube URL.
     */
    public static function youtubePosterUrl(?string $url): ?string
    {
        $id = self::youtubeId($url);

        return $id ? 'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg' : null;
    }

    protected static function validId(string $id): ?string
    {
        $id = trim($id);

        return preg_match('/^[A-Za-z0-9_-]{6,}$/', $id) ? $id : null;
    }
}
