<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Traits\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductDetail;
use DB;

class productController extends Controller
{
    use ResponseHelper;

    public function list(Request $request)
    {
        $products = Product::with('details')->where([['user_id',Auth::id()],['is_delete',0]])->get();

        return $this->successResponse($products, 200, 'Successfully returned all products.');
    }

    public function view(Request $request,$id)
    {
        $product = Product::with('details')->where([['id',$id],['is_delete',0]])->first();

        return $this->successResponse($product, 200, 'Successfully returned requested product.');
    }

    public function store(Request $request)
    {
        $rules = [

            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048', // up to 2MB
            'category_id' => 'required',
            'gender_id' => 'required',
            'age_type' => 'required',
            'age' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
        ];

        $messages = [
            'category_id.required' => 'Category is required.',
            'gender_id.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
        ];

        $validator=Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(),"The given data was invalid.");
        }

        DB::beginTransaction();

        // In your controller store method
        $lastProduct = Product::where('user_id',Auth::id())->latest('id')->first();

        if ($lastProduct) {
            // Extract number part from unique_id and increment
            $lastNumber = (int) str_replace('P-', '', $lastProduct->unique_id);
            $newNumber = $lastNumber + 1;
        } else {
            // If no product yet, start from 1
            $newNumber = 1;
        }

        // Format with leading zeros (5 digits)
        $uniqueId = 'P-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $product = Product::create([ 
            'user_id' => Auth::id(),
            'unique_id' => $uniqueId,
            'status' => 1,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender_id,
            'category_id' => $request->category_id,
            'age_type' => $request->age_type,
            'age' => $request->age,
            'weight' => $request->weight,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'products';

            // Save the file
            $filePath = $file->storeAs($path, $filename, 'public');

            // Save to user
            $product_detail->image = $filePath; // This is relative to storage/app/public
            $product_detail->save();
        }

        DB::commit();

        return $this->successResponse('Success', 200, 'Successfully saved the product.');
    }

    public function update(Request $request)
    {

        $rules = [
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048', // up to 2MB
            'category_id' => 'required',
            'gender_id' => 'required',
            'age_type' => 'required',
            'age' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'status' => 'required',
        ];
        $messages = [
            'category_id.required' => 'Category is required.',
            'gender_id.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
            'status.required'   => 'Status is required.',
        ];

        $validator=Validator::make($request->all(),$rules,$messages);

        if ($validator->fails()) {
            return $this->validationFailed($validator->errors(),"The given data was invalid.");
        }

        DB::beginTransaction();

        // In your controller store method
        $product = Product::where('id',$request->product_id)->first();
        $detail = ProductDetail::where([['product_id',$request->product_id],['is_delete',0]])->latest('id')->first();

        $product->update([ 
            'status' => $request->status,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender_id,
            'category_id' => $request->category_id,
            'age_type' => $request->age_type,
            'age' => $request->age,
            'weight' => $request->weight,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = 'products';

            // Save the file
            $filePath = $file->storeAs($path, $filename, 'public');

            // Save to user
            $product_detail->image = $filePath; // This is relative to storage/app/public
            $product_detail->save();
        }
        else
        {
            
            $product_detail = ProductDetail::where([['id',$product_detail->id],['is_delete',0]])->first();
            $product_detail->update(['image'=> $detail->image]);
        }


        DB::commit();

        return $this->successResponse('Success', 200, 'Successfully updated the product.');

    }

    public function delete(Request $request,$id)
    {
        $product = Product::with('details')->where('id',$id)->update(['is_delete' => 1]);

        return $this->successResponse('Success', 200, 'Product deleted successfully.');
    }

    public function detail_delete(Request $request,$id)
    {

        $product = ProductDetail::where('id',$id)->update(['is_delete'=> 1]);

        return $this->successResponse('Success', 200, 'Product detail deleted successfully.');
    }
}
