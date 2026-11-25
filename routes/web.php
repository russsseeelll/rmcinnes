<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LlmsController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::get('/portfolio/{portfolio:slug}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::get('/experience/create', [AdminController::class, 'experience'])->name('experience.create');
    Route::get('/experience/{experience}/edit', [AdminController::class, 'experience'])->name('experience.edit');
    Route::get('/education/create', [AdminController::class, 'education'])->name('education.create');
    Route::get('/education/{education}/edit', [AdminController::class, 'education'])->name('education.edit');
    Route::get('/toolkit/create', [AdminController::class, 'toolkit'])->name('toolkit.create');
    Route::get('/toolkit/{skillGroup}/edit', [AdminController::class, 'toolkit'])->name('toolkit.edit');
    Route::get('/profile/edit', [AdminController::class, 'profile'])->name('profile.edit');
    Route::get('/strava', [AdminController::class, 'strava'])->name('strava.edit');
    Route::get('/leaderboard', [AdminController::class, 'leaderboard'])->name('leaderboard.edit');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});

Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');
Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/cv', CvController::class)->name('cv');
Route::get('/llms.txt', LlmsController::class)->name('llms');
Route::get('/favicon.svg', fn () => response()->view('favicon')->header('Content-Type', 'image/svg+xml')->header('Cache-Control', 'public, max-age=86400'))->name('favicon');
Route::get('/extras', StatusController::class)->name('extras');
Route::redirect('/status', '/extras#status', 301);

Route::get('/scores', [ScoreController::class, 'index'])->name('scores');

Route::view('/login', 'login')->middleware('guest');
Route::post('/login', [UserController::class, 'authenticate'])->middleware(['guest', 'throttle:5,1'])->name('login');
