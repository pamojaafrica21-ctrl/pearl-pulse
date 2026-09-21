<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Enquiry extends Model
{
    public const STATUS_NEW = 'new';

    public const STATUS_IN_PROGRESS = 'in_progress';

    public const STATUS_PROPOSAL_SENT = 'proposal_sent';

    public const STATUS_WON = 'won';

    public const STATUS_LOST = 'lost';

    public const CHANNEL_WEBSITE = 'website';

    public const CHANNEL_FINDER = 'journey_finder';

    public const CHANNEL_WHATSAPP = 'whatsapp';

    public const CHANNEL_PHONE = 'phone';

    public const CHANNEL_WALK_IN = 'walk_in';

    public const CHANNEL_AGENT = 'agent';

    /** @var array<int, string> */
    public const STATUSES = [
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_PROPOSAL_SENT,
        self::STATUS_WON,
        self::STATUS_LOST,
    ];

    /** @var array<int, string> */
    public const OPEN_STATUSES = [
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_PROPOSAL_SENT,
    ];

    /** @var array<int, string> */
    public const CHANNELS = [
        self::CHANNEL_WEBSITE,
        self::CHANNEL_FINDER,
        self::CHANNEL_WHATSAPP,
        self::CHANNEL_PHONE,
        self::CHANNEL_WALK_IN,
        self::CHANNEL_AGENT,
    ];

    protected $fillable = [
        'user_id',
        'assigned_user_id',
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
        'channel',
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

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    public function booking(): HasOne
    {
        return $this->hasOne(Booking::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(PipelineNote::class, 'noteable')->latest();
    }

    public function markAs(string $status): void
    {
        if (! in_array($status, self::STATUSES, true)) {
            return;
        }

        $this->update(['status' => $status]);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', self::OPEN_STATUSES);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'In progress',
            self::STATUS_PROPOSAL_SENT => 'Proposal sent',
            self::STATUS_WON => 'Won',
            self::STATUS_LOST => 'Lost',
            default => 'New',
        };
    }

    public function guestStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_IN_PROGRESS => 'In conversation',
            self::STATUS_PROPOSAL_SENT => 'Proposal sent',
            self::STATUS_WON => $this->booking?->guestStatusLabel() ?? 'Confirmed',
            self::STATUS_LOST => 'Closed',
            default => 'Received',
        };
    }

    public function channelLabel(): string
    {
        return match ($this->channel) {
            self::CHANNEL_FINDER => 'Journey Finder',
            self::CHANNEL_WHATSAPP => 'WhatsApp',
            self::CHANNEL_PHONE => 'Phone',
            self::CHANNEL_WALK_IN => 'Walk-in',
            self::CHANNEL_AGENT => 'Agent',
            default => $this->journey?->name
                ?? $this->destination?->name
                ?? 'Website',
        };
    }

    public function isOpen(): bool
    {
        return in_array($this->status, self::OPEN_STATUSES, true);
    }

    public static function matchingUserId(?string $email): ?int
    {
        if (app()->bound('request')) {
            $user = auth()->user();
            if ($user?->isCustomer()) {
                return $user->id;
            }
        }

        if (! filled($email)) {
            return null;
        }

        return User::query()
            ->where('email', $email)
            ->where('role', 'customer')
            ->value('id');
    }
}
