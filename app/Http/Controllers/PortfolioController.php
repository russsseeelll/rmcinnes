<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function portfolio(Request $request)
    {
        return view('portfolio', [
            'portfolios' => Portfolio::filtered($request->input('search'), $request->input('category'))->paginate(8)->withQueryString(),
            'categories' => Portfolio::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    public function portfoliosingle(Portfolio $portfolio)
    {
        return view('project', ['portfolio' => $portfolio]);
    }

    public function create()
    {
        return view('admin.project-form', ['portfolio' => new Portfolio(['date' => now()->toDateString()])]);
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.project-form', ['portfolio' => $portfolio]);
    }
}
