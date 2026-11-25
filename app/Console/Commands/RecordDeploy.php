<?php

namespace App\Console\Commands;

use App\Models\Deploy;
use Illuminate\Console\Command;

class RecordDeploy extends Command
{
    protected $signature = 'deploy:record
        {--commit= : Full commit SHA (defaults to the checked-out HEAD)}
        {--message= : Commit subject (defaults to HEAD\'s)}
        {--run-url= : Link to the CI run}
        {--duration= : Pipeline duration in seconds}';

    protected $description = 'Record a deploy so the footer and Extras page can show it';

    public function handle(): int
    {
        $commit = $this->option('commit') ?: trim((string) shell_exec('git -C '.escapeshellarg(base_path()).' rev-parse HEAD 2>/dev/null'));
        if (! preg_match('/^[0-9a-f]{7,40}$/', $commit)) {
            $this->error('No valid commit SHA. Pass --commit or run inside the git checkout.');

            return self::FAILURE;
        }

        $message = $this->option('message') ?: trim((string) shell_exec('git -C '.escapeshellarg(base_path()).' log -1 --format=%s 2>/dev/null'));
        $duration = $this->option('duration');

        Deploy::create([
            'commit' => $commit,
            'message' => $message !== '' ? mb_substr($message, 0, 255) : null,
            'run_url' => $this->option('run-url') ?: null,
            'duration_seconds' => is_numeric($duration) ? (int) $duration : null,
            'deployed_at' => now(),
        ]);

        $this->info('Recorded deploy '.substr($commit, 0, 7).'.');

        return self::SUCCESS;
    }
}
