<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasGallery;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasVideo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Experience extends Model
{
    use HasCover, HasGallery, HasSeo, HasVideo, Publishable;

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'teaser',
        'description',
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
            'is_featured' => 'boolean',
        ];
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class);
    }

    public function journeys(): BelongsToMany
    {
        return $this->belongsToMany(Journey::class, 'journey_experience');
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')->orderBy('sort_order');
    }
}
