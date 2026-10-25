<?php

namespace App\Services;

use App\Models\InstagramComment;
use App\Models\InstagramMedia;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class InstagramGraphService
{
    private const GRAPH_VERSION = 'v21.0';

    private const MEDIA_FIELDS = 'id,caption,media_type,media_product_type,media_url,thumbnail_url,permalink,timestamp,like_count,comments_count';

    public function __construct(private SettingService $settings) {}

    public function isConfigured(): bool
    {
        return filled($this->userId()) && filled($this->accessToken());
    }

    public function userId(): string
    {
        return trim((string) $this->settings->get('instagram_user_id', ''));
    }

    public function accessToken(): string
    {
        return trim((string) $this->settings->get('instagram_access_token', ''));
    }

    public function hashtag(): string
    {
        $tag = trim((string) $this->settings->get('instagram_hashtag', '#PearlPulseSafaris'));

        if ($tag === '') {
            return '#PearlPulseSafaris';
        }

        return str_starts_with($tag, '#') ? $tag : '#'.$tag;
    }

    /**
     * Sync recent media + comments into the local cache.
     *
     * @return array{synced: int, comments: int}
     */
    public function sync(int $limit = 25): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Instagram is not configured. Add user ID and access token in Settings.');
        }

        $synced = 0;
        $commentCount = 0;
        $url = sprintf(
            'https://graph.facebook.com/%s/%s/media',
            self::GRAPH_VERSION,
            $this->userId()
        );

        $remaining = $limit;
        $after = null;

        while ($remaining > 0) {
            $pageSize = min(25, $remaining);
            $query = [
                'fields' => self::MEDIA_FIELDS,
                'limit' => $pageSize,
                'access_token' => $this->accessToken(),
            ];

            if ($after) {
                $query['after'] = $after;
            }

            $response = $this->client()->get($url, $query);

            if (! $response->successful()) {
                throw new RuntimeException($this->errorMessage($response->json(), $response->status()));
            }

            $payload = $response->json();
            $items = $payload['data'] ?? [];

            if ($items === []) {
                break;
            }

            foreach ($items as $item) {
                $media = $this->upsertMedia($item);
                $synced++;
                $commentCount += $this->syncComments($media);
                $remaining--;

                if ($remaining <= 0) {
                    break;
                }
            }

            $after = $payload['paging']['cursors']['after'] ?? null;
            if (! $after || count($items) < $pageSize) {
                break;
            }
        }

        return ['synced' => $synced, 'comments' => $commentCount];
    }

    /**
     * Refresh short-lived CDN URLs for a single media item.
     */
    public function refreshMediaUrls(InstagramMedia $media): InstagramMedia
    {
        if (! $this->isConfigured()) {
            return $media;
        }

        $response = $this->client()->get(
            sprintf('https://graph.facebook.com/%s/%s', self::GRAPH_VERSION, $media->ig_id),
            [
                'fields' => self::MEDIA_FIELDS,
                'access_token' => $this->accessToken(),
            ]
        );

        if (! $response->successful()) {
            Log::warning('Instagram media URL refresh failed', [
                'ig_id' => $media->ig_id,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return $media;
        }

        return $this->upsertMedia($response->json());
    }

    public function syncComments(InstagramMedia $media): int
    {
        $response = $this->client()->get(
            sprintf('https://graph.facebook.com/%s/%s/comments', self::GRAPH_VERSION, $media->ig_id),
            [
                'fields' => 'id,username,text,timestamp',
                'limit' => 50,
                'access_token' => $this->accessToken(),
            ]
        );

        if (! $response->successful()) {
            Log::warning('Instagram comments sync failed', [
                'ig_id' => $media->ig_id,
                'status' => $response->status(),
            ]);

            return 0;
        }

        $seen = [];
        $count = 0;

        foreach ($response->json('data') ?? [] as $comment) {
            $igId = (string) ($comment['id'] ?? '');
            if ($igId === '') {
                continue;
            }

            $seen[] = $igId;

            InstagramComment::query()->updateOrCreate(
                ['ig_id' => $igId],
                [
                    'instagram_media_id' => $media->id,
                    'username' => $comment['username'] ?? null,
                    'text' => $comment['text'] ?? null,
                    'posted_at' => isset($comment['timestamp'])
                        ? date('Y-m-d H:i:s', strtotime($comment['timestamp']))
                        : null,
                ]
            );
            $count++;
        }

        if ($seen !== []) {
            InstagramComment::query()
                ->where('instagram_media_id', $media->id)
                ->whereNotIn('ig_id', $seen)
                ->delete();
        } else {
            InstagramComment::query()
                ->where('instagram_media_id', $media->id)
                ->delete();
        }

        return $count;
    }

    private function upsertMedia(array $item): InstagramMedia
    {
        $igId = (string) ($item['id'] ?? '');
        if ($igId === '') {
            throw new RuntimeException('Instagram media payload missing id.');
        }

        return InstagramMedia::query()->updateOrCreate(
            ['ig_id' => $igId],
            [
                'media_type' => $item['media_type'] ?? 'IMAGE',
                'media_product_type' => $item['media_product_type'] ?? null,
                'caption' => $item['caption'] ?? null,
                'permalink' => $item['permalink'] ?? null,
                'media_url' => $item['media_url'] ?? null,
                'thumbnail_url' => $item['thumbnail_url'] ?? null,
                'like_count' => (int) ($item['like_count'] ?? 0),
                'comments_count' => (int) ($item['comments_count'] ?? 0),
                'posted_at' => isset($item['timestamp'])
                    ? date('Y-m-d H:i:s', strtotime($item['timestamp']))
                    : null,
                'raw' => $item,
            ]
        );
    }

    private function client(): PendingRequest
    {
        return Http::timeout(30)->acceptJson();
    }

    private function errorMessage(?array $json, int $status): string
    {
        $message = $json['error']['message'] ?? null;

        if (is_string($message) && $message !== '') {
            return 'Instagram API error: '.$message;
        }

        return 'Instagram API request failed (HTTP '.$status.').';
    }
}
