<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Journey extends Model
{
    use HasCover, HasGallery, HasSeo, Publishable;

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'teaser',
        'overview',
        'days',
        'duration_label',
        'itinerary',
        'highlights',
        'included',
        'not_included',
        'best_time',
        'practical',
        'price_mode',
        'price_from',
        'map_embed_url',
        'is_signature',
        'is_multi_country',
        'is_featured',
        'cover_path',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'itinerary' => 'array',
            'highlights' => 'array',
            'included' => 'array',
            'not_included' => 'array',
            'is_signature' => 'boolean',
            'is_multi_country' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'journey_country');
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'journey_destination');
    }

    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(Experience::class, 'journey_experience');
    }

    public function stays(): BelongsToMany
    {
        return $this->belongsToMany(Stay::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }

    public function scopeSignature(Builder $query): Builder
    {
        return $query->where('is_signature', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function priceLabel(): string
    {
        return match ($this->price_mode) {
            'tailored' => 'Tailored to your journey',
            'proposal' => 'Request a private proposal',
            default => $this->price_from ?: 'From — on request',
        };
    }
}
