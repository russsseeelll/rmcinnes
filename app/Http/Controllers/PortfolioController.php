<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PortfolioController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function portfolio(Request $request)
    {
        return view('portfolio', [
            'portfolios' => $this->filtered($request)->paginate(8)->withQueryString(),
            'categories' => Portfolio::query()->distinct()->orderBy('category')->pluck('category'),
        ]);
    }

    private function filtered(Request $request)
    {
        $query = Portfolio::query();

        if ($request->input('search')) {
            $query->where('title', 'LIKE', '%'.$request->input('search').'%')
                ->orWhere('tags', 'LIKE', '%'.$request->input('search').'%')
                ->orWhere('category', 'LIKE', '%'.$request->input('search').'%');
        }

        if ($request->input('category')) {
            $query->where('category', $request->input('category'));
        }

        return $query->orderBy('date', 'desc');
    }

    public function portfoliosingle(Portfolio $portfolio)
    {
        return view('project', ['portfolio' => $portfolio]);
    }

    public function create()
    {
        return view('admin.project-form', ['portfolio' => new Portfolio(['date' => now()->toDateString()])]);
    }

    public function store(Request $request)
    {
        $portfolio = Portfolio::create($this->fields($request));

        return redirect()->route('portfolio.single', $portfolio->slug)->with('message', 'Project added.');
    }

    public function edit(Portfolio $portfolio)
    {
        return view('admin.project-form', ['portfolio' => $portfolio]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $portfolio->update($this->fields($request, $portfolio));

        return redirect()->route('portfolio.single', $portfolio->slug)->with('message', 'Changes saved.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $this->deleteImage($portfolio->image);
        $portfolio->delete();

        return redirect()->route('portfolio')->with('message', "“{$portfolio->title}” deleted.");
    }

    /** Validated fields for create and update, with the image stored or removed as asked. */
    private function fields(Request $request, ?Portfolio $portfolio = null): array
    {
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('portfolio', 'title')->ignore($portfolio)],
            'date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:255'],
            'tags' => ['nullable', 'string', 'max:255'],
            'summary' => ['required', 'string', 'max:512'],
            'description' => ['required', 'string', 'max:3000'],
            'link' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10000'],
            'remove_image' => ['nullable', 'boolean'],
        ]);
        unset($fields['remove_image']);

        if ($request->hasFile('image')) {
            $this->deleteImage($portfolio?->image);
            // Kept under public/storage/images, where existing project images already live.
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
