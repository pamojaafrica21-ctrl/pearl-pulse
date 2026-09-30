<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasCover, HasSeo, Publishable;

    protected $fillable = [
        'name',
        'slug',
        'subtitle',
        'teaser',
        'description',
        'cover_path',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
