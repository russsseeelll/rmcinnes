<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PortfolioController;

Route::get('/', [ToolController::class, 'tools']);

Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');

Route::get('/portfolio/create', [PortfolioController::class, 'create'])->name('portfolio.create');


Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');

Route::put('/portfolio/{portfolio}', [PortfolioController::class, 'update'])->name('portfolio.update');

Route::post('/portfolio', [PortfolioController::class, 'store'])->name('portfolio.store');

Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

Route::get('/services', function () {
    return view('services');
});

Route::get('/services', function () {
    return view('services');
});

Route::get('/portfolio/{id}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');