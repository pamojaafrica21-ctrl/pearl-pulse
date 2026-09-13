<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasSeo, Publishable;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'status',
        'sort_order',
    ];
}
