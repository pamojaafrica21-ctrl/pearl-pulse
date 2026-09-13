<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Faq extends Model
{
    use Publishable;

    protected $fillable = [
        'question',
        'answer',
        'group',
        'faqable_type',
        'faqable_id',
        'status',
        'sort_order',
    ];

    public function faqable(): MorphTo
    {
        return $this->morphTo();
    }
}
