<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StravaController;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', [ToolController::class, 'tools']);

Route::middleware('auth')->group(function () {
    Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');
    Route::get('/portfolio/{portfolio:slug}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio/{portfolio:slug}', [PortfolioController::class, 'update'])->name('portfolio.update');
    Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');
    Route::delete('/portfolio/{portfolio:slug}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
});

Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');
Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/services', function () {
    return view('services');
});
Route::get('/login', function () {
    return view('login');
});

Route::get('/auth/strava', [StravaController::class, 'redirectToStrava']);
Route::get('/auth/strava/callback', [StravaController::class, 'handleStravaCallback']);
Route::get('/strava/fastest-5k', [StravaController::class, 'getFastest5k']);

Route::post('/login', [App\Http\Controllers\UserController::class, 'authenticate'])->name('login');
