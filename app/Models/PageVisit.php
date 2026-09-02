<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'session_id',
        'visited_at',
    ];

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
        ];
    }

    public static function record(string $path, string $sessionId): void
    {
        static::query()->create([
            'path' => $path,
            'session_id' => $sessionId,
            'visited_at' => now(),
        ]);
    }

    public static function stats(): array
    {
        $today = now()->toDateString();
        $weekAgo = now()->subDays(7);
        $monthAgo = now()->subDays(30);

        return [
            'total_views' => static::query()->count(),
            'today_views' => static::query()->whereDate('visited_at', $today)->count(),
            'week_views' => static::query()->where('visited_at', '>=', $weekAgo)->count(),
            'month_views' => static::query()->where('visited_at', '>=', $monthAgo)->count(),
            'today_unique' => static::query()
                ->whereDate('visited_at', $today)
                ->distinct()
                ->count('session_id'),
            'week_unique' => static::query()
                ->where('visited_at', '>=', $weekAgo)
                ->distinct()
                ->count('session_id'),
            'month_unique' => static::query()
                ->where('visited_at', '>=', $monthAgo)
                ->distinct()
                ->count('session_id'),
        ];
    }
}
