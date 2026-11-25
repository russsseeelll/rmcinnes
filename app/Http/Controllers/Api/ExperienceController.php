<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        return ['data' => Experience::orderByDesc('started_on')->get()->map($this->present(...))];
    }

    public function show(Experience $experience)
    {
        return ['data' => $this->present($experience)];
    }

    public function store(Request $request)
    {
        return response()->json(['data' => $this->present(Experience::create($this->fields($request)))], 201);
    }

    public function update(Request $request, Experience $experience)
    {
        $experience->update($this->fields($request, $experience));

        return ['data' => $this->present($experience->fresh())];
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return response()->noContent();
    }

    private function fields(Request $request, ?Experience $experience = null): array
    {
        if (is_array($request->input('highlights'))) {
            $request->merge(['highlights' => implode("\n", $request->input('highlights'))]);
        }

        $required = $experience ? 'sometimes' : 'required';
        $fields = $request->validate([
            'title' => [$required, 'string', 'max:255'],
            'organisation' => [$required, 'string', 'max:255'],
            'started_on' => [$required, 'date'],
            'ended_on' => ['nullable', 'date', 'after_or_equal:'.($request->input('started_on') ?? $experience?->started_on?->toDateString() ?? 'started_on')],
            'highlights' => ['nullable', 'string', 'max:5000'],
        ], [
            'ended_on.after_or_equal' => "The end date can't be before the start date.",
        ], [
            'started_on' => 'start date',
            'ended_on' => 'end date',
        ]);

        if (array_key_exists('highlights', $fields)) {
            $lines = array_filter(array_map('trim', explode("\n", (string) $fields['highlights'])));
            $fields['highlights'] = implode("\n", array_map(fn ($l) => ltrim($l, "-•* \t"), $lines)) ?: null;
        }

        return $fields;
    }

    private function present(Experience $e): array
    {
        return [
            'id' => $e->id,
            'title' => $e->title,
            'organisation' => $e->organisation,
            'started_on' => $e->started_on->toDateString(),
            'ended_on' => $e->ended_on?->toDateString(),
            'period' => $e->period(),
            'highlights' => $e->highlightList(),
            'url' => route('home').'#experience',
        ];
    }
}
