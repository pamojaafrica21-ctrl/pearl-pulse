<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Country extends Model
{
    use HasCover, HasGallery, HasSeo, Publishable;

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'teaser',
        'description',
        'practical',
        'best_time',
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
            'is_featured' => 'boolean',
        ];
    }

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class)->orderBy('sort_order')->orderBy('name');
    }

    public function journeys(): BelongsToMany
    {
        return $this->belongsToMany(Journey::class, 'journey_country')->orderBy('sort_order');
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }
}
