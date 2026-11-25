<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Portfolio;
use App\Models\SkillGroup;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'experiences' => Experience::orderByDesc('started_on')->get(),
            'education' => Education::orderBy('sort_order')->get(),
            'skillGroups' => SkillGroup::orderBy('sort_order')->get(),
            'projects' => Portfolio::orderByDesc('date')->take(3)->get(),
        ]);
    }
}
