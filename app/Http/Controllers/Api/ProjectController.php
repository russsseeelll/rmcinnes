<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return ProjectResource::collection(Portfolio::filtered($request->input('search'), $request->input('category'))->get());
    }

    public function show(Portfolio $portfolio)
    {
        return new ProjectResource($portfolio);
    }

    public function store(Request $request)
    {
        $portfolio = Portfolio::create($this->fields($request));

        return (new ProjectResource($portfolio))->response()->setStatusCode(201);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $portfolio->update($this->fields($request, $portfolio));

        return new ProjectResource($portfolio->fresh());
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->deleteImage($portfolio->image);
        $portfolio->delete();

        return response()->noContent();
    }

    private function fields(Request $request, ?Portfolio $portfolio = null): array
    {
        if (is_array($request->input('tags'))) {
            $request->merge(['tags' => implode(', ', array_filter(array_map('trim', $request->input('tags'))))]);
        }

        $required = $portfolio ? 'sometimes' : 'required';
        $fields = $request->validate([
            'title' => [$required, 'string', 'max:255', Rule::unique('portfolio', 'title')->ignore($portfolio)],
            'date' => [$required, 'date'],
            'category' => [$required, 'string', 'max:255'],
            'tags' => ['nullable', 'string', 'max:255'],
            'summary' => [$required, 'string', 'max:512'],
            'description' => [$required, 'string', 'max:3000'],
            'link' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10000'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
        unset($fields['remove_image']);
        if (array_key_exists('tags', $fields) || ! $portfolio) {
            $fields['tags'] = $fields['tags'] ?? '';
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($portfolio?->image);
            $file = $request->file('image');
            $file->move(public_path('storage/images'), $name = $file->hashName());
            $fields['image'] = 'storage/images/'.$name;
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImage($portfolio?->image);
            $fields['image'] = null;
        } else {
            unset($fields['image']);
        }

        return $fields;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && File::isFile(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}
