<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::get('/portfolio/{portfolio:slug}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolio:slug}', [PortfolioController::class, 'update'])->name('portfolio.update');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::delete('/portfolio/{portfolio:slug}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');
Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');
Route::post('/contact', [ContactController::class, 'store']);
Route::get('/cv', CvController::class)->name('cv');
Route::get('/status', StatusController::class)->name('status');

Route::get('/scores', [ScoreController::class, 'index'])->name('scores');

Route::view('/login', 'login')->middleware('guest');
Route::post('/login', [UserController::class, 'authenticate'])->middleware(['guest', 'throttle:5,1'])->name('login');
