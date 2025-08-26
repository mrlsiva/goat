<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use DB;

class categoryController extends Controller
{
    public function index()
    { 
        $categories = Category::where('is_active',1)->paginate(10);
        return view('categories.index',compact('categories'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:50|unique:categories',
        ], 
        [
            'name.required' => 'Name is required.',
        ]);

        DB::beginTransaction();

        $category = Category::create([ 
            'name' => Str::ucfirst($request->name),
            'is_active' => 1,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request)
    {
        

        $request->validate([
            'category' => ['required','string','max:50',
                Rule::unique('categories', 'name')->ignore($request->category_id), // specify column if needed
            ],
            'category_id' => 'required',
        ], 
        [
            'category.required' => 'Name is required.',
        ]);


        DB::beginTransaction();

        $category = Category::find($request->category_id);

        $category->update([ 
            'name' => Str::ucfirst($request->category)
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Category updated successfully.');
    }



}
