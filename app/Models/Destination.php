<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasVideo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasCover, HasSeo, HasVideo, Publishable;

    protected $fillable = [
        'name',
        'subtitle',
        'slug',
        'region',
        'country_id',
        'teaser',
        'duration',
        'best_time',
        'activities',
        'price_from',
        'description',
        'why',
        'practical',
        'highlights',
        'cover_path',
        'video_path',
        'video_url',
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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(DestinationImage::class)->orderBy('sort_order');
    }

    public function enquiries(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class);
    }

    public function journeys(): BelongsToMany
    {
        return $this->belongsToMany(Journey::class, 'journey_destination');
    }

    public function stays(): BelongsToMany
    {
        return $this->belongsToMany(Stay::class);
    }

    public function pulseItems(): BelongsToMany
    {
        return $this->belongsToMany(PulseItem::class);
    }

    public function primaryStays(): HasMany
    {
        return $this->hasMany(Stay::class);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function publicUrl(): string
    {
        $countrySlug = $this->country?->slug ?? 'east-africa';

        return route('destinations.show', [$countrySlug, $this->slug]);
    }
}
