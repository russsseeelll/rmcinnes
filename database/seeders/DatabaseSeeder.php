<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ProfileSeeder::class, ProjectSeeder::class, ScoreSeeder::class]);

        if (class_exists(AdminUserSeeder::class)) {
            $this->call(AdminUserSeeder::class);
        }
    }
}
