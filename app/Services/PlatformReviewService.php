<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Fetches actual guest reviews from the Google Places API and the
 * Tripadvisor Content API. Credentials and listing IDs are managed in
 * Admin → Site settings (Reviews — Google & Tripadvisor). Results are
 * cached for six hours; nothing is fetched until both the listing ID
 * and API key for a platform are set.
 */
class PlatformReviewService
{
    public function __construct(protected SettingService $settings) {}

    /**
     * @return array{
     *     google: ?array{rating: ?float, count: ?int, url: string, name: string},
     *     tripadvisor: ?array{rating: ?float, count: ?int, url: string, name: string},
     *     reviews: list<array{platform: string, author: string, avatar: ?string, rating: ?float, date: string, title: string, text: string, url: string}>
     * }
     */
    public function summary(): array
    {
        $fingerprint = substr(hash('sha256', implode('|', [
            (string) $this->settings->get('review_google_place_id', ''),
            (string) $this->settings->get('review_google_api_key', ''),
            (string) $this->settings->get('review_tripadvisor_location_id', ''),
            (string) $this->settings->get('review_tripadvisor_api_key', ''),
        ])), 0, 16);

        return Cache::remember(
            'platform_reviews:'.$fingerprint,
            now()->addHours(6),
            fn () => $this->fetch()
        );
    }

    public function configured(string $platform): bool
    {
        return match ($platform) {
            'google' => trim((string) $this->settings->get('review_google_place_id', '')) !== ''
                && trim((string) $this->settings->get('review_google_api_key', '')) !== '',
            'tripadvisor' => trim((string) $this->settings->get('review_tripadvisor_location_id', '')) !== ''
                && trim((string) $this->settings->get('review_tripadvisor_api_key', '')) !== '',
            default => false,
        };
    }

    protected function fetch(): array
    {
        $google = $this->configured('google') ? $this->fetchGoogle() : null;
        $tripadvisor = $this->configured('tripadvisor') ? $this->fetchTripadvisor() : null;

        return [
            'google' => $google['summary'] ?? null,
            'tripadvisor' => $tripadvisor['summary'] ?? null,
            'reviews' => $this->interleave($google['reviews'] ?? [], $tripadvisor['reviews'] ?? []),
        ];
    }

    protected function fetchGoogle(): ?array
    {
        $placeId = trim((string) $this->settings->get('review_google_place_id', ''));
        $apiKey = trim((string) $this->settings->get('review_google_api_key', ''));

        try {
            $response = Http::acceptJson()
                ->timeout(8)
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => 'displayName,rating,userRatingCount,googleMapsUri,reviews',
                ])
                ->get('https://places.googleapis.com/v1/places/'.urlencode($placeId));

            if (! $response->successful()) {
                Log::warning('Google Places review fetch failed', ['status' => $response->status()]);

                return null;
            }

            $data = $response->json();
            $platformUrl = (string) ($data['googleMapsUri'] ?? $this->settings->get('review_google_url', ''));

            $reviews = collect($data['reviews'] ?? [])
                ->map(function (array $review) use ($platformUrl) {
                    $text = trim((string) ($review['text']['text'] ?? $review['originalText']['text'] ?? ''));
                    if ($text === '') {
                        return null;
                    }

                    return [
                        'platform' => 'Google',
                        'author' => (string) ($review['authorAttribution']['displayName'] ?? 'Google guest'),
                        'avatar' => $review['authorAttribution']['photoUri'] ?? null,
                        'rating' => isset($review['rating']) ? (float) $review['rating'] : null,
                        'date' => (string) ($review['relativePublishTimeDescription'] ?? $this->formatDate($review['publishTime'] ?? null)),
                        'title' => '',
                        'text' => $text,
                        'url' => (string) ($review['authorAttribution']['uri'] ?? $platformUrl),
                    ];
                })
                ->filter()
                ->values()
                ->all();

            return [
                'summary' => [
                    'rating' => isset($data['rating']) ? (float) $data['rating'] : null,
                    'count' => isset($data['userRatingCount']) ? (int) $data['userRatingCount'] : null,
                    'url' => $platformUrl,
                    'name' => (string) ($data['displayName']['text'] ?? 'Pearl Pulse Safaris'),
                ],
                'reviews' => $reviews,
            ];
        } catch (\Throwable $e) {
            Log::warning('Google Places review fetch error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    protected function fetchTripadvisor(): ?array
    {
        $locationId = trim((string) $this->settings->get('review_tripadvisor_location_id', ''));
        $apiKey = trim((string) $this->settings->get('review_tripadvisor_api_key', ''));
        $base = 'https://api.content.tripadvisor.com/api/v1/location/'.urlencode($locationId);

        try {
            $details = Http::acceptJson()
                ->timeout(8)
                ->get($base.'/details', ['key' => $apiKey, 'language' => 'en']);

            $reviewsResponse = Http::acceptJson()
                ->timeout(8)
                ->get($base.'/reviews', ['key' => $apiKey, 'language' => 'en', 'limit' => 6]);

            if (! $reviewsResponse->successful() && ! $details->successful()) {
                Log::warning('Tripadvisor review fetch failed', [
                    'details_status' => $details->status(),
                    'reviews_status' => $reviewsResponse->status(),
                ]);

                return null;
            }

            $detailData = $details->successful() ? (array) $details->json() : [];
            $platformUrl = (string) ($detailData['web_url'] ?? $this->settings->get('review_tripadvisor_url', ''));

            $reviews = collect($reviewsResponse->successful() ? ($reviewsResponse->json('data') ?? []) : [])
                ->map(function (array $review) use ($platformUrl) {
                    $text = trim((string) ($review['text'] ?? ''));
                    if ($text === '') {
                        return null;
                    }

                    return [
                        'platform' => 'Tripadvisor',
                        'author' => (string) ($review['user']['username'] ?? 'Tripadvisor guest'),
                        'avatar' => $review['user']['avatar']['small'] ?? $review['user']['avatar']['medium'] ?? null,
                        'rating' => isset($review['rating']) ? (float) $review['rating'] : null,
                        'date' => $this->formatDate($review['published_date'] ?? $review['travel_date'] ?? null),
                        'title' => trim((string) ($review['title'] ?? '')),
                        'text' => $text,
                        'url' => (string) ($review['url'] ?? $platformUrl),
                    ];
                })
                ->filter()
                ->values()
                ->all();

            return [
                'summary' => [
                    'rating' => isset($detailData['rating']) ? (float) $detailData['rating'] : null,
                    'count' => isset($detailData['num_reviews']) ? (int) $detailData['num_reviews'] : null,
                    'url' => $platformUrl,
                    'name' => (string) ($detailData['name'] ?? 'Pearl Pulse Safaris'),
                ],
                'reviews' => $reviews,
            ];
        } catch (\Throwable $e) {
            Log::warning('Tripadvisor review fetch error', ['message' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Alternate reviews from each platform so both are represented.
     */
    protected function interleave(array $google, array $tripadvisor, int $limit = 6): array
    {
        $mixed = [];
        $max = max(count($google), count($tripadvisor));

        for ($i = 0; $i < $max && count($mixed) < $limit; $i++) {
            if (isset($google[$i])) {
                $mixed[] = $google[$i];
            }
            if (count($mixed) < $limit && isset($tripadvisor[$i])) {
                $mixed[] = $tripadvisor[$i];
            }
        }

        return $mixed;
    }

    protected function formatDate(mixed $value): string
    {
        if (! is_string($value) || $value === '') {
            return '';
        }

        try {
            return Carbon::parse($value)->format('M Y');
        } catch (\Throwable) {
            return Str::limit($value, 20, '');
        }
    }
}
