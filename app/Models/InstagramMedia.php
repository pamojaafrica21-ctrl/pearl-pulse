<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstagramMedia extends Model
{
    protected $table = 'instagram_media';

    protected $fillable = [
        'ig_id',
        'media_type',
        'media_product_type',
        'caption',
        'permalink',
        'media_url',
        'thumbnail_url',
        'like_count',
        'comments_count',
        'posted_at',
        'raw',
    ];

    protected function casts(): array
    {
        return [
            'like_count' => 'integer',
            'comments_count' => 'integer',
            'posted_at' => 'datetime',
            'raw' => 'array',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(InstagramComment::class)->orderByDesc('posted_at');
    }

    public function isVideo(): bool
    {
        return strtoupper((string) $this->media_type) === 'VIDEO'
            || strtoupper((string) $this->media_product_type) === 'REELS';
    }

    public function coverUrl(): ?string
    {
        if ($this->isVideo()) {
            return $this->thumbnail_url ?: $this->media_url;
        }

        return $this->media_url ?: $this->thumbnail_url;
    }

    public function relativePostedAt(): string
    {
        if (! $this->posted_at) {
            return '';
        }

        return $this->posted_at->diffForHumans(null, true, true);
    }

    public function formattedLikeCount(): string
    {
        return self::formatCount($this->like_count);
    }

    public function formattedCommentsCount(): string
    {
        return self::formatCount($this->comments_count);
    }

    public static function formatCount(int $count): string
    {
        if ($count >= 1000000) {
            return rtrim(rtrim(number_format($count / 1000000, 1), '0'), '.').'m';
        }

        if ($count >= 1000) {
            return rtrim(rtrim(number_format($count / 1000, 1), '0'), '.').'k';
        }

        return (string) $count;
    }
}
