<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductDetail;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;
use App\Models\Gender;
use DB;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class productController extends Controller
{
    public function index(Request $request)
    {  
        $products = Product::paginate(10);
        return view('products.index',compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::where('is_active',1)->get();
        $genders = Gender::where('is_active',1)->get();

        return view('products.create',compact('categories','genders'));
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048', // up to 2MB
            'category' => 'required',
            'gender' => 'required',
            'age_type' => 'required',
            'age' => 'required',
            'weight' => 'required',
        ], 
        [
            'category.required' => 'Category is required.',
            'gender.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
        ]);

        DB::beginTransaction();

        // In your controller store method
        $lastProduct = Product::latest('id')->first();

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
            'unique_id' => $uniqueId,
            'status' => 1,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender,
            'category_id' => $request->category,
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

        return redirect('products/index')->with('success', 'Product created successfully.');

    }

    public function view(Request $request,$id)
    {
        $product = Product::with('details')->findOrFail($id);

        return view('products.view', compact('product'));
    }

    public function edit(Request $request,$id)
    {

        $categories = Category::where('is_active',1)->get();
        $genders = Gender::where('is_active',1)->get();

        $product = Product::with('details')->findOrFail($id);

        return view('products.edit', compact('product','categories','genders'));
    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048', // up to 2MB
            'category' => 'required',
            'gender' => 'required',
            'age_type' => 'required',
            'age' => 'required',
            'weight' => 'required',
        ], 
        [
            'category.required' => 'Category is required.',
            'gender.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
        ]);

        DB::beginTransaction();

        // In your controller store method
        $product = Product::find($request->id)->first();
        $detail = ProductDetail::latest('product_id',$request->id)->first();

        $product->update([ 
            'status' => $request->status,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender,
            'category_id' => $request->category,
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
            
            $product_detail = ProductDetail::where('id',$product_detail->id)->first();
            $product_detail->update(['image'=> $detail->image]);
        }


        DB::commit();

        return redirect()->back()->with('success', 'Product updated successfully.');

    }

    public function download(Request $request,$id)
    {
        $product = Product::with('details')->findOrFail($id);

        $qrCode = QrCode::size(300)->generate(url('/products/'.$product->id.'/view'));

        return view('products.qrcode', compact('qrCode','product'));
        
    }

    public function download_all(Request $request)
    {
        $products = Product::with('details')->get();

        return view('products.all_qrcode', compact('products'));

    }

}
