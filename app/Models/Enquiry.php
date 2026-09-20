<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = [
        'user_id',
        'destination_id',
        'journey_id',
        'name',
        'email',
        'phone',
        'message',
        'preferred_destinations',
        'days',
        'travellers',
        'preferred_experiences',
        'accommodation',
        'investment',
        'travel_dates',
        'preferences',
        'whatsapp',
        'status',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'preferred_destinations' => 'array',
            'preferred_experiences' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    public function markAs(string $status): void
    {
        $this->update(['status' => $status]);
    }
}
