<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'subscribed_at',
    ];

    protected function casts(): array
    {
        return [
            'subscribed_at' => 'datetime',
        ];
    }

    public static function subscribe(string $email): self
    {
        $email = strtolower(trim($email));

        return static::query()->updateOrCreate(
            ['email' => $email],
            ['subscribed_at' => now()]
        );
    }
}
