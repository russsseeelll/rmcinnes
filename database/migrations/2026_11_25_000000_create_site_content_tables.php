<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('headline');
            $table->string('location')->nullable();
            $table->string('email');
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->text('intro');
            $table->string('contact_heading')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('organisation');
            $table->date('started_on');
            $table->date('ended_on')->nullable();
            $table->text('highlights')->nullable();
            $table->timestamps();
        });

        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('qualification');
            $table->string('institution')->nullable();
            $table->string('period')->nullable();
            $table->string('grade')->nullable();
            $table->boolean('in_progress')->default(false);
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('skill_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('skills');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::dropIfExists('tools');
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_groups');
        Schema::dropIfExists('education');
        Schema::dropIfExists('experiences');
        Schema::dropIfExists('profiles');

        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('image');
            $table->timestamps();
        });
    }
};
