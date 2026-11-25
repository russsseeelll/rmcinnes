<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Score;
use App\Models\SkillGroup;
use App\Services\Strava;

class AdminController extends Controller
{
    public function experience(?Experience $experience = null)
    {
        return view('admin.experience-form', ['experience' => $experience ?? new Experience]);
    }

    public function education(?Education $education = null)
    {
        return view('admin.education-form', ['education' => $education ?? new Education]);
    }

    public function toolkit(?SkillGroup $skillGroup = null)
    {
        return view('admin.skill-group-form', ['group' => $skillGroup ?? new SkillGroup]);
    }

    public function profile()
    {
        return view('admin.profile-form');
    }

    public function strava(Strava $strava)
    {
        return view('admin.strava', ['strava' => $strava]);
    }

    public function leaderboard()
    {
        return view('admin.leaderboard', ['scores' => Score::orderByDesc('score')->orderBy('created_at')->get()]);
    }
}
