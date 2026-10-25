<?php

namespace App\Console\Commands;

use App\Services\InstagramGraphService;
use Illuminate\Console\Command;
use Throwable;

class SyncInstagramFeed extends Command
{
    protected $signature = 'instagram:sync {--limit=25 : Max media items to sync}';

    protected $description = 'Sync Instagram media and comments into the local True Pulse cache';

    public function handle(InstagramGraphService $instagram): int
    {
        if (! $instagram->isConfigured()) {
            $this->warn('Instagram is not configured — skipping sync.');

            return self::SUCCESS;
        }

        try {
            $result = $instagram->sync((int) $this->option('limit'));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Synced %d media items and %d comments.',
            $result['synced'],
            $result['comments']
        ));

        return self::SUCCESS;
    }
}
