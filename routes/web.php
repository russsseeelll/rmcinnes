<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\PortfolioController;

Route::get('/', [ToolController::class, 'tools']);

Route::get('/portfolio', [PortfolioController::class, 'portfolio'])->name('portfolio');

Route::get('/portfolio/{id}', [PortfolioController::class, 'portfoliosingle'])->name('portfolio.single');

Route::get('/portfolio/{portfolio}/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');

Route::put('/portfolio/{portfolio}', [PortfolioController::class, 'update'])->name('portfolio.update');

Route::delete('/portfolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolio.destroy');

Route::get('/services', function () {
    return view('services');
});

Route::get('/edit-listings', function () {
    return view('components.edit-portfolio');
});