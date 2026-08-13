<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\ImageUploader;

class Destination extends Model
{
    protected $fillable = [
        'name',
        'subtitle',
        'slug',
        'region',
        'country',
        'teaser',
        'duration',
        'best_time',
        'activities',
        'price_from',
        'description',
        'highlights',
        'cover_path',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class)->orderBy('sort_order');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function coverUrl(): ?string
    {
        return app(ImageUploader::class)->url($this->cover_path);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function seoTitle(): string
    {
        return $this->meta_title ?: $this->name.' | '.config('app.name');
    }

    public function seoDescription(): string
    {
        return $this->meta_description ?: ($this->teaser ?: strip_tags((string) $this->description));
    }
}
