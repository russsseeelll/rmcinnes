<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\SkillGroup;

class CvController extends Controller
{
    public function __invoke()
    {
        return view('cv', [
            'experiences' => Experience::orderByDesc('started_on')->get(),
            'education' => Education::orderByDesc('featured')->orderBy('sort_order')->get(),
            'skillGroups' => SkillGroup::orderBy('sort_order')->get(),
        ]);
    }
}
