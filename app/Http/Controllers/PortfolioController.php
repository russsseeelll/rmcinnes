<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class PortfolioController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function portfolio(Request $request)
    {
        $query = Portfolio::query();

        // Check if a search term has been entered
        if ($request->input('search')) {
            $query->where('title', 'LIKE', '%'.$request->input('search').'%')
                  ->orWhere('tags', 'LIKE', '%'.$request->input('search').'%')
                  ->orWhere('category', 'LIKE', '%'.$request->input('search').'%');
        }

        // Check if a category filter has been applied
        if ($request->input('category')) {
            $query->where('category', $request->input('category'));
        }

        $portfolios = $query->paginate(6);

        return view('portfolio', ['portfolios' => $portfolios]);

    }


    public function portfoliosingle(Portfolio $portfolio)
    {
        if (!$portfolio) {
            return redirect('/login');
        }
        return view('portfolio-single', ['portfolio' => $portfolio]);
    }

    public function create()
    {
        return view("components.create-portfolio");
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            "title" => ['required', 'unique:portfolio'],
            "date" => "required",
            "category" => "required",
            "tags" => "nullable",
            "summary" => ['required', 'max:512'],
            "description" => ['required', 'max:3000'],
            "link" => "nullable",
            "image" => "image|mimes:jpeg,png,jpg,gif|max:10000",
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            $formFields['image'] = 'storage/images/' . $imageName;
        }

        // Check if 'tags' and 'link' fields are empty. If they are, set them to null or some default value.
        $formFields['tags'] = empty($formFields['tags']) ? null : $formFields['tags'];
        $formFields['link'] = empty($formFields['link']) ? null : $formFields['link'];
        $formFields['slug'] = Str::slug($formFields['title'], '-');

        Portfolio::create($formFields);

        return redirect("/portfolio")->with("message", "Entry created successfully!");
    }


    public function edit(Portfolio $portfolio){
        if (!$portfolio) {
            return redirect('/login');
        }
        return view('components.edit-portfolio', ['portfolio' => $portfolio]);
    }


    public function update(Request $request, Portfolio $portfolio)
    {
        $formFields = $request->validate([
            "title" => "required",
            "date" => "required",
            "category" => "required",
            "tags" => "nullable",
            "summary" => ['required', 'max:513'],
            "description" => ['required', 'max:3000'],
            "link" => "nullable",
            "image" => "image|mimes:jpeg,png,jpg,gif|max:10000",
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            $formFields['image'] = 'storage/images/' . $imageName;
        }

        // Create slug from title outside the if block so it always gets updated
        $formFields['slug'] = Str::slug($formFields['title'], '-');

        $portfolio->update($formFields);

        return redirect()->route('portfolio')
                         ->with("message", "Entry updated successfully!");
    }




    public function destroy(Portfolio $portfolio)
    {

        if (!$portfolio) {
            return redirect('/login');
        }

        $title = $portfolio->title;
        $category = $portfolio->category;


        $imagePath = public_path($portfolio->image);
        if(File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $portfolio->delete();

        return redirect('/portfolio')->with('message', 'You have successfully deleted "' . $title . '" from the category "' . $category . '".');
    }



    public function manage() {
        return view('portfolio.manage', ['portfolios' => Portfolio::all()]);
    }
}
