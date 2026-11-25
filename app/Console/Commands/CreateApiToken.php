<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiToken extends Command
{
    protected $signature = 'api:token
        {user? : Username to issue the token for (defaults to the only user)}
        {--name=api : Label for the token, e.g. "claude"}';

    protected $description = 'Create an API token that can add, edit and delete projects';

    public function handle(): int
    {
        $user = $this->argument('user')
            ? User::where('name', $this->argument('user'))->first()
            : (User::count() === 1 ? User::first() : null);

        if (! $user) {
            $this->error(User::count() === 0
                ? 'No users yet. Create one first (see README, "Admin login").'
                : 'Which user? Pass a username: php artisan api:token <username>');

            return self::FAILURE;
        }

        $token = $user->createToken($this->option('name'), ['projects:write'])->plainTextToken;

        $this->info("Token for {$user->name} (shown once, store it somewhere safe):");
        $this->line($token);

        return self::SUCCESS;
    }
}
