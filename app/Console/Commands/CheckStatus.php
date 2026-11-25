<?php

namespace App\Console\Commands;

use App\Models\HealthCheck;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class CheckStatus extends Command
{
    protected $signature = 'status:check';

    protected $description = 'Check the site responds and record the result';

    public function handle(): int
    {
        $started = hrtime(true);
        try {
            $response = Http::timeout(10)->withUserAgent('rmcinnes-status-check')->get(url('/'));
            $ok = $response->successful();
            $code = $response->status();
        } catch (Throwable) {
            $ok = false;
            $code = null;
        }

        HealthCheck::create([
            'ok' => $ok,
            'status_code' => $code,
            'response_ms' => (int) round((hrtime(true) - $started) / 1e6),
            'checked_at' => now(),
        ]);
        HealthCheck::where('checked_at', '<', now()->subDays(90))->delete();

        $this->line($ok ? 'up' : 'down');

        return self::SUCCESS;
    }
}
