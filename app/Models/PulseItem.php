<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasVideo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PulseItem extends Model
{
    use HasCover, HasVideo, Publishable;

    public const TYPES = ['photo', 'reel', 'story'];

    protected $fillable = [
        'title',
        'type',
        'caption',
        'guest_name',
        'cover_path',
        'video_path',
        'video_url',
        'approved',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'approved' => 'boolean',
        ];
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true);
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->published()->approved();
    }
}
