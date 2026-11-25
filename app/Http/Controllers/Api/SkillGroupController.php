<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SkillGroup;
use Illuminate\Http\Request;

class SkillGroupController extends Controller
{
    public function index()
    {
        return ['data' => SkillGroup::orderBy('sort_order')->get()->map($this->present(...))];
    }

    public function show(SkillGroup $skillGroup)
    {
        return ['data' => $this->present($skillGroup)];
    }

    public function store(Request $request)
    {
        $fields = $this->fields($request);
        $fields['sort_order'] ??= (SkillGroup::max('sort_order') ?? -1) + 1;

        return response()->json(['data' => $this->present(SkillGroup::create($fields))], 201);
    }

    public function update(Request $request, SkillGroup $skillGroup)
    {
        $skillGroup->update($this->fields($request, $skillGroup));

        return ['data' => $this->present($skillGroup->fresh())];
    }

    public function destroy(SkillGroup $skillGroup)
    {
        $skillGroup->delete();

        return response()->noContent();
    }

    private function fields(Request $request, ?SkillGroup $group = null): array
    {
        $skills = $request->input('skills');
        if (is_array($skills) || is_string($skills)) {
            $list = is_array($skills) ? $skills : preg_split('/[,\n]/', $skills);
            $request->merge(['skills' => implode(', ', array_unique(array_filter(array_map('trim', $list))))]);
        }

        $required = $group ? 'sometimes' : 'required';

        $fields = $request->validate([
            'name' => [$required, 'string', 'max:255'],
            'skills' => [$required, 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
        if (array_key_exists('sort_order', $fields) && $fields['sort_order'] === null) {
            unset($fields['sort_order']);
        }

        return $fields;
    }

    private function present(SkillGroup $g): array
    {
        return [
            'id' => $g->id,
            'name' => $g->name,
            'skills' => $g->skillList(),
            'sort_order' => $g->sort_order,
            'url' => route('home').'#toolkit',
        ];
    }
}
