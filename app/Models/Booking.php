<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    public const STATUS_HELD = 'held';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_IN_TRAVEL = 'in_travel';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    /** @var array<int, string> */
    public const STATUSES = [
        self::STATUS_HELD,
        self::STATUS_CONFIRMED,
        self::STATUS_IN_TRAVEL,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    protected $fillable = [
        'reference',
        'enquiry_id',
        'user_id',
        'journey_id',
        'assigned_user_id',
        'created_by',
        'channel',
        'name',
        'email',
        'phone',
        'whatsapp',
        'travellers',
        'start_date',
        'end_date',
        'travel_dates',
        'investment',
        'status',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function enquiry(): BelongsTo
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function journey(): BelongsTo
    {
        return $this->belongsTo(Journey::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(PipelineNote::class, 'noteable')->latest();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_IN_TRAVEL => 'Travelling',
            self::STATUS_COMPLETED => 'Travelled',
            self::STATUS_CANCELLED => 'Cancelled',
            default => 'Held',
        };
    }

    public function guestStatusLabel(): string
    {
        return $this->statusLabel();
    }

    public function channelLabel(): string
    {
        return match ($this->channel) {
            Enquiry::CHANNEL_FINDER => 'Journey Finder',
            Enquiry::CHANNEL_WHATSAPP => 'WhatsApp',
            Enquiry::CHANNEL_PHONE => 'Phone',
            Enquiry::CHANNEL_WALK_IN => 'Walk-in',
            Enquiry::CHANNEL_AGENT => 'Agent',
            Enquiry::CHANNEL_WEBSITE => 'Website',
            default => 'Direct',
        };
    }

    public function datesLabel(): ?string
    {
        if ($this->start_date && $this->end_date) {
            if ($this->start_date->isSameMonth($this->end_date)) {
                return $this->start_date->format('d').'–'.$this->end_date->format('d M Y');
            }

            if ($this->start_date->isSameYear($this->end_date)) {
                return $this->start_date->format('d M').' – '.$this->end_date->format('d M Y');
            }

            return $this->start_date->format('d M Y').' – '.$this->end_date->format('d M Y');
        }

        if ($this->start_date) {
            return 'From '.$this->start_date->format('d M Y');
        }

        return $this->travel_dates;
    }

    public function nights(): ?int
    {
        if (! $this->start_date || ! $this->end_date) {
            return null;
        }

        $nights = $this->start_date->diffInDays($this->end_date);

        return $nights > 0 ? (int) $nights : null;
    }

    public function timingLabel(): string
    {
        return match (true) {
            $this->status === self::STATUS_CANCELLED => 'Cancelled',
            $this->status === self::STATUS_IN_TRAVEL => 'On journey',
            $this->status === self::STATUS_COMPLETED => $this->end_date
                ? 'Travelled '.$this->end_date->format('Y')
                : 'Travelled',
            ! $this->start_date => 'Dates TBC',
            $this->start_date->isToday() => 'Starts today',
            $this->start_date->isPast() => 'Dates passed',
            default => 'In '.$this->start_date->diffForHumans(now(), true),
        };
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            self::STATUS_CONFIRMED => 'bg-forest/10 text-forest border-forest/15',
            self::STATUS_IN_TRAVEL => 'bg-forest text-sand border-forest',
            self::STATUS_COMPLETED => 'bg-sand text-muted border-sand-deep/40',
            self::STATUS_CANCELLED => 'bg-charcoal/5 text-muted border-charcoal/10',
            default => 'bg-gold/15 text-gold border-gold/25',
        };
    }

    public static function nextReference(?Carbon $at = null): string
    {
        $at ??= now();
        $year = (int) $at->year;
        $count = static::query()->whereYear('created_at', $year)->count() + 1;

        return sprintf('PP-%d-%04d', $year, $count);
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    public static function parseTravelDates(?string $travelDates): array
    {
        $travelDates = trim((string) $travelDates);
        if ($travelDates === '') {
            return [null, null];
        }

        if (preg_match('/^(\d{4}-\d{2}-\d{2})\s+to\s+(\d{4}-\d{2}-\d{2})$/', $travelDates, $matches)) {
            return [$matches[1], $matches[2]];
        }

        if (preg_match('/^From\s+(\d{4}-\d{2}-\d{2})$/', $travelDates, $matches)) {
            return [$matches[1], null];
        }

        if (preg_match('/^Until\s+(\d{4}-\d{2}-\d{2})$/', $travelDates, $matches)) {
            return [null, $matches[1]];
        }

        return [null, null];
    }

    public static function fromEnquiry(Enquiry $enquiry, array $overrides = []): self
    {
        [$start, $end] = self::parseTravelDates($enquiry->travel_dates);

        $booking = static::query()->create(array_merge([
            'reference' => self::nextReference(),
            'enquiry_id' => $enquiry->id,
            'user_id' => $enquiry->user_id,
            'journey_id' => $enquiry->journey_id,
            'assigned_user_id' => $enquiry->assigned_user_id,
            'created_by' => auth()->id(),
            'channel' => $enquiry->channel,
            'name' => $enquiry->name,
            'email' => $enquiry->email,
            'phone' => $enquiry->phone,
            'whatsapp' => $enquiry->whatsapp,
            'travellers' => $enquiry->travellers,
            'start_date' => $start,
            'end_date' => $end,
            'travel_dates' => $enquiry->travel_dates,
            'investment' => $enquiry->investment,
            'status' => self::STATUS_HELD,
            'summary' => $enquiry->preferences,
        ], $overrides));

        $enquiry->markAs(Enquiry::STATUS_WON);

        return $booking;
    }
}
