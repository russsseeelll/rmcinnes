<?php

namespace App\Http\Controllers;

use App\Models\Score;

class ScoreController extends Controller
{
    public function index()
    {
        return Score::top();
    }
}
