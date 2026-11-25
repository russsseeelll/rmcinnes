<?php

use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\ExperienceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ScoreController;
use App\Http\Controllers\Api\SkillGroupController;
use App\Http\Controllers\Api\StravaController;
use Illuminate\Support\Facades\Route;

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{portfolio:slug}', [ProjectController::class, 'show']);
Route::get('/experience', [ExperienceController::class, 'index']);
Route::get('/experience/{experience}', [ExperienceController::class, 'show']);
Route::get('/education', [EducationController::class, 'index']);
Route::get('/education/{education}', [EducationController::class, 'show']);
Route::get('/skill-groups', [SkillGroupController::class, 'index']);
Route::get('/skill-groups/{skillGroup}', [SkillGroupController::class, 'show']);
Route::get('/profile', [ProfileController::class, 'show']);

Route::middleware(['auth:sanctum', 'abilities:projects:write'])->group(function () {
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::match(['put', 'patch'], '/projects/{portfolio:slug}', [ProjectController::class, 'update']);
    Route::delete('/projects/{portfolio:slug}', [ProjectController::class, 'destroy']);

    Route::post('/experience', [ExperienceController::class, 'store']);
    Route::match(['put', 'patch'], '/experience/{experience}', [ExperienceController::class, 'update']);
    Route::delete('/experience/{experience}', [ExperienceController::class, 'destroy']);

    Route::post('/education', [EducationController::class, 'store']);
    Route::match(['put', 'patch'], '/education/{education}', [EducationController::class, 'update']);
    Route::delete('/education/{education}', [EducationController::class, 'destroy']);

    Route::post('/skill-groups', [SkillGroupController::class, 'store']);
    Route::match(['put', 'patch'], '/skill-groups/{skillGroup}', [SkillGroupController::class, 'update']);
    Route::delete('/skill-groups/{skillGroup}', [SkillGroupController::class, 'destroy']);

    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update']);

    Route::get('/strava', [StravaController::class, 'show']);
    Route::match(['put', 'patch'], '/strava', [StravaController::class, 'update']);
    Route::delete('/strava', [StravaController::class, 'destroy']);
    Route::post('/strava/sync', [StravaController::class, 'sync']);

    Route::get('/scores', [ScoreController::class, 'index']);
    Route::post('/scores', [ScoreController::class, 'store']);
    Route::match(['put', 'patch'], '/scores/{score}', [ScoreController::class, 'update']);
    Route::delete('/scores/{score}', [ScoreController::class, 'destroy']);
});
