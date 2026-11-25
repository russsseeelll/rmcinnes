<?php

namespace App\Http\Controllers;

use App\Models\Score;

class ScoreController extends Controller
{
    /** The cluster game's leaderboard (read-only; entries come from ScoreSeeder). */
    public function index()
    {
        return Score::top();
    }
}
