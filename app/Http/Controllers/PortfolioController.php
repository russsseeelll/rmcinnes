<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
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
    
        $portfolios = $query->paginate(10);
    
        return view('portfolio', ['portfolios' => $portfolios]);

    }
    

    public function portfoliosingle($id)
    {
        $portfolio = Portfolio::find($id);
        return view('portfolio-single', ['portfolio' => $portfolio]);
    }



    public function create()
    {
        return view("components.portfolio.create");
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            "title" => "required",
            "date" => "required",
            "category" => "required",
            "tags",
            "summary" => "required",
            "description" => "required",
            "link",
            "image" => "required|image|mimes:jpeg,png,jpg,gif|max:2048", // Add image validation rules
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $imageName); // Store the image in the storage/app/public/images directory
            $formFields['image'] = 'storage/images/' . $imageName; // Save the image path in the database
        }
    
        Portfolio::create($formFields);
    
        return redirect("/")->with("message", "Entry created successfully!");
    }
    

    public function edit(Portfolio $portfolio){
        return view('components.edit-portfolio', ['portfolio' => $portfolio]);
    }
    

    public function update(Request $request, Portfolio $portfolio)
    {
        $formFields = $request->validate([
            "title" => "required",
            "date" => "required",
            "category" => "required",
            "tags",
            "summary" => "required",
            "description" => "required",
            "link",
            "image" => "image|mimes:jpeg,png,jpg,gif|max:2048", // Modify the image validation rules if necessary
        ]);
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            $formFields['image'] = 'storage/images/' . $imageName;
        }
    
        $portfolio->update($formFields);
    
        return redirect()->route('portfolio')
                         ->with("message", "Entry updated successfully!");
    }
    
    

    public function destroy(Portfolio $portfolio)
    {
        $title = $portfolio->title; 
        $category = $portfolio->category;
    
        $portfolio->delete();
    
        return redirect('/portfolio')->with('message', 'You have successfully deleted "' . $title . '" from the category "' . $category . '".');
    }
    

    public function manage() {
        return view('portfolio.manage', ['portfolios' => Portfolio::all()]);
    }
}