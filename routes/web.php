<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return (new \App\Http\Controllers\ToolController())->tools();
});

Route::get('/portfolio/create', function () {
    return (new \App\Http\Controllers\PortfolioController())->create();
})->name('portfolio.create')->middleware('auth');

Route::get('/portfolio/{portfolio:slug}/edit', function (\App\Models\Portfolio $portfolio) {
    return (new \App\Http\Controllers\PortfolioController())->edit($portfolio);
})->name('portfolio.edit')->middleware('auth');

Route::put('/portfolio/{portfolio:slug}', function (\Illuminate\Http\Request $request, \App\Models\Portfolio $portfolio) {
    return (new \App\Http\Controllers\PortfolioController())->update($request, $portfolio);
})->name('portfolio.update')->middleware('auth');

Route::post('/portfolio', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\PortfolioController())->store($request);
})->name('portfolio.store')->middleware('auth');

Route::delete('/portfolio/{portfolio:slug}', function (\App\Models\Portfolio $portfolio) {
    return (new \App\Http\Controllers\PortfolioController())->destroy($portfolio);
})->name('portfolio.destroy')->middleware('auth');

Route::get('/portfolio/{portfolio:slug}', function (\App\Models\Portfolio $portfolio) {
    return (new \App\Http\Controllers\PortfolioController())->portfoliosingle($portfolio);
})->name('portfolio.single');

Route::get('/portfolio', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\PortfolioController())->portfolio($request);
})->name('portfolio');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    return (new \App\Http\Controllers\ContactController())->store($request);
});

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
