<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ContactController;

Route::get('/', [ToolController::class, 'tools']);

Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create')->middleware('auth');

Route::get('/portfolio/{portfolio:slug}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit')->middleware('auth');

Route::put('/portfolio/{portfolio:slug}', [PortfolioController::class, 'update'])->name('portfolio.update')->middleware('auth');

Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store')->middleware('auth');

Route::delete('/portfolio/{portfolio:slug}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy')->middleware('auth');

Route::get('/portfolio/{portfolio:slug}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');

Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');

Route::post('/contact', [ContactController::class, 'store']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout')->middleware('auth');

Route::get('/services', function () {
    return view('services');
});

Route::get('/login', function () {
    return view('login');
});

Route::post('/login', 'App\Http\Controllers\UserController@authenticate')->name('login');
