<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasCover, HasSeo, Publishable;

    public const TYPES = ['guide', 'practical', 'field'];

    protected $fillable = [
        'title',
        'slug',
        'type',
        'excerpt',
        'body',
        'cover_path',
        'meta_title',
        'meta_description',
        'is_featured',
        'status',
        'sort_order',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'practical' => 'Practical',
            'field' => 'From the Field',
            default => 'Travel Guide',
        };
    }
}
