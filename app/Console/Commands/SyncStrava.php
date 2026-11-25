<?php

namespace App\Console\Commands;

use App\Services\Strava;
use Illuminate\Console\Command;
use Throwable;

class SyncStrava extends Command
{
    protected $signature = 'strava:sync';

    protected $description = 'Fetch this year\'s running totals from Strava for the homepage';

    public function handle(Strava $strava): int
    {
        if (! $strava->configured()) {
            $this->line('Strava is not configured (set STRAVA_CLIENT_ID, STRAVA_CLIENT_SECRET and STRAVA_REFRESH_TOKEN). Skipping.');

            return self::SUCCESS;
        }

        try {
            $s = $strava->sync();
        } catch (Throwable $e) {
            report($e);
            $this->error('Strava sync failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf('%s km over %d runs in %d, average pace %s /km.', $s['km'], $s['runs'], $s['year'], Strava::formatPace($s['pace_seconds_per_km']) ?? '-'));

        return self::SUCCESS;
    }
}
