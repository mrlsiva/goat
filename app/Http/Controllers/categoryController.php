<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use DB;

class categoryController extends Controller
{
    public function index()
    { 
        $categories = Category::where('user_id',Auth::id())->paginate(10);
        return view('categories.index',compact('categories'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => ['required','string','max:20',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ], 
        [
            'name.required' => 'Name is required.',
        ]);

        DB::beginTransaction();

        $category = Category::create([ 
            'user_id' => Auth::id(),
            'name' => Str::ucfirst($request->name),
            'is_active' => 1,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function update(Request $request)
    {
        

        $request->validate([
            'category' => ['required','string','max:20',
                Rule::unique('categories', 'name')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($request->category_id), // ignore current category id
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
