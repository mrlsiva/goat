<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use DB;

class categoryController extends Controller
{
    use ResponseHelper;

    public function list(Request $request)
    {
        $categories = Category::where('user_id',Auth::id())->get();

        return $this->successResponse($categories, 200, 'Successfully returned all categories.');
    }

    public function active_list(Request $request)
    {
        $categories = Category::where([['user_id',Auth::id()],['is_active',1]])->get();

        return $this->successResponse($categories, 200, 'Successfully returned all active categories.');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => ['required','string','max:50',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.unique'   => 'You already have a category with this name.',
        ];

        $validator=Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(),"The given data was invalid.");
        }

        DB::beginTransaction();

        $category = Category::create([ 
            'user_id' => Auth::id(),
            'name' => Str::ucfirst($request->name),
            'is_active' => 1,
        ]);

        DB::commit();

        return $this->successResponse($category, 200, 'Category Saved Successfully.');
    }

    public function update(Request $request)
    {

        $rules = [
            'name' => ['required','string','max:50',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($request->category_id), // ignore current category id
            ],
            'is_active' => 'required',
        ];

        $messages = [
            'name.required' => 'Name is required.',
            'name.unique'   => 'You already have a category with this name.',
            'is_active.required' => 'Active status is required.',
        ];

        $validator=Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(),"The given data was invalid.");
        }

        DB::beginTransaction();

        $category = Category::find($request->category_id);

        $category->update([ 
            'name' => Str::ucfirst($request->name),
            'is_active' => $request->is_active,
        ]);

        DB::commit();

        return $this->successResponse($category, 200, 'Category Updated Successfully.');

    }
}
