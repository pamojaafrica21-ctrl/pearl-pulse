<?php

namespace App\Models;

use App\Models\Concerns\HasCover;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasCover, HasSeo, Publishable;

    protected $fillable = [
        'name',
        'slug',
        'role',
        'bio',
        'cover_path',
        'status',
        'sort_order',
    ];
}
