<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enquiry extends Model
{
    protected $fillable = [
        'destination_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function markAs(string $status): void
    {
        $this->update(['status' => $status]);
    }
}
