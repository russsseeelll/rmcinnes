<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        return ['data' => Education::orderBy('sort_order')->get()->map($this->present(...))];
    }

    public function show(Education $education)
    {
        return ['data' => $this->present($education)];
    }

    public function store(Request $request)
    {
        $fields = $this->fields($request);
        $fields['sort_order'] ??= (Education::max('sort_order') ?? -1) + 1;

        return response()->json(['data' => $this->present(Education::create($fields))], 201);
    }

    public function update(Request $request, Education $education)
    {
        $education->update($this->fields($request, $education));

        return ['data' => $this->present($education->fresh())];
    }

    public function destroy(Education $education)
    {
        $education->delete();

        return response()->noContent();
    }

    private function fields(Request $request, ?Education $education = null): array
    {
        $fields = $request->validate([
            'qualification' => [$education ? 'sometimes' : 'required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'period' => ['nullable', 'string', 'max:255'],
            'grade' => ['nullable', 'string', 'max:255'],
            'in_progress' => ['sometimes', 'boolean'],
            'featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        if (array_key_exists('sort_order', $fields) && $fields['sort_order'] === null) {
            unset($fields['sort_order']);
        }

        return $fields;
    }

    private function present(Education $e): array
    {
        return [
            'id' => $e->id,
            'qualification' => $e->qualification,
            'institution' => $e->institution,
            'period' => $e->period,
            'grade' => $e->grade,
            'in_progress' => $e->in_progress,
            'featured' => $e->featured,
            'sort_order' => $e->sort_order,
            'url' => route('home').'#education',
        ];
    }
}
