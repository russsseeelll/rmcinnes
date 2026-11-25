<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function index()
    {
        return ['data' => Score::orderByDesc('score')->orderBy('created_at')->get()->map($this->present(...))];
    }

    public function store(Request $request)
    {
        return response()->json(['data' => $this->present(Score::create($this->fields($request)))], 201);
    }

    public function update(Request $request, Score $score)
    {
        $score->update($this->fields($request, $score));

        return ['data' => $this->present($score->fresh())];
    }

    public function destroy(Score $score)
    {
        $score->delete();

        return response()->noContent();
    }

    private function fields(Request $request, ?Score $score = null): array
    {
        $required = $score ? 'sometimes' : 'required';

        return $request->validate([
            'name' => [$required, 'string', 'max:40'],
            'organisation' => ['nullable', 'string', 'max:40'],
            'score' => [$required, 'integer', 'min:0', 'max:999'],
        ]);
    }

    private function present(Score $s): array
    {
        return ['id' => $s->id, 'name' => $s->name, 'organisation' => $s->organisation, 'score' => $s->score, 'url' => route('leaderboard.edit')];
    }
}
