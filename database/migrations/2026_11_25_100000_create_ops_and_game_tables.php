<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Recorded by `php artisan deploy:record` at the end of each deploy.
        Schema::create('deploys', function (Blueprint $table) {
            $table->id();
            $table->string('commit', 40);
            $table->string('message')->nullable();
            $table->string('run_url')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->timestamp('deployed_at')->index();
        });

        // Written by the scheduled `status:check` command.
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();
            $table->boolean('ok');
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->unsignedInteger('response_ms')->nullable();
            $table->timestamp('checked_at')->index();
        });

        // Cluster game leaderboard.
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->string('organisation', 40)->nullable();
            $table->unsignedInteger('score')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
        Schema::dropIfExists('health_checks');
        Schema::dropIfExists('deploys');
    }
};
