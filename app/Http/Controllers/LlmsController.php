<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Portfolio;
use App\Models\Profile;
use App\Models\SkillGroup;

class LlmsController extends Controller
{
    public function __invoke()
    {
        $body = view('llms', [
            'profile' => Profile::current(),
            'experiences' => Experience::orderByDesc('started_on')->get(),
            'education' => Education::orderByDesc('featured')->orderBy('sort_order')->get(),
            'skillGroups' => SkillGroup::orderBy('sort_order')->get(),
            'projects' => Portfolio::filtered()->get(),
        ])->render();

        return response(preg_replace("/\n{3,}/", "\n\n", trim($body))."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
