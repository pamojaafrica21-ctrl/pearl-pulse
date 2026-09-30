<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Faq extends Model
{
    use Publishable;

    public const TOPICS = [
        'permits' => 'Permits',
        'best_time' => 'Best time to travel',
        'inclusions' => 'Inclusions',
        'families' => 'Families',
        'payment_cancellation' => 'Payment & cancellation',
        'health_visas' => 'Health & visas',
    ];

    protected $fillable = [
        'question',
        'answer',
        'group',
        'topic',
        'faqable_type',
        'faqable_id',
        'status',
        'sort_order',
    ];

    public function faqable(): MorphTo
    {
        return $this->morphTo();
    }

    public function topicLabel(): string
    {
        return self::TOPICS[$this->topic] ?? 'General';
    }
}
