<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        return ['data' => $this->present(Profile::current())];
    }

    public function update(Request $request)
    {
        $profile = Profile::query()->first();
        $required = $profile ? 'sometimes' : 'required';

        $fields = $request->validate([
            'name' => [$required, 'string', 'max:255'],
            'headline' => [$required, 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'email' => [$required, 'email', 'max:255'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'intro' => [$required, 'string', 'max:2000'],
            'ai_summary' => ['nullable', 'string', 'max:3000'],
            'contact_heading' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'show_mascot' => ['sometimes', 'boolean'],
        ]);

        $profile ? $profile->update($fields) : $profile = Profile::create($fields);

        return ['data' => $this->present($profile->fresh())];
    }

    private function present(Profile $p): array
    {
        return [
            'name' => $p->name,
            'headline' => $p->headline,
            'location' => $p->location,
            'email' => $p->email,
            'github_url' => $p->github_url,
            'linkedin_url' => $p->linkedin_url,
            'intro' => $p->intro,
            'ai_summary' => $p->ai_summary,
            'contact_heading' => $p->contact_heading,
            'meta_description' => $p->meta_description,
            'show_mascot' => (bool) ($p->show_mascot ?? true),
            'url' => route('home').'#about',
        ];
    }
}
