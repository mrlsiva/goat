<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Exports\ProductExport;
use Illuminate\Validation\Rule;
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
        $query = Product::where([
            ['user_id', Auth::id()],
            ['is_delete', 0],
        ]);

        if ($request->filled('product_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('unique_id', 'like', "%{$request->product_id}%")
                  ->orWhere('unique_number', 'like', "%{$request->product_id}%");
            });
        }

        $products = $query->paginate(10);
        return view('products.index',compact('products'));
    }

    public function create(Request $request)
    {
        $categories = Category::where([['is_active',1],['user_id',Auth::id()]])->get();
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
            'age' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'purchased_amount' => 'required|numeric|min:1',
            'unique_number' => ['nullable','string','max:20',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
        ], 
        [
            'category.required' => 'Category is required.',
            'gender.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
            'purchased_amount.required'   => 'Purchased Amount is required.',
        ]);

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
            'unique_number' => $request->unique_number,
            'status' => 1,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender,
            'category_id' => $request->category,
            'age_type' => $request->age_type,
            'age' => $request->age,
            'weight' => $request->weight,
            'purchased_amount' => $request->purchased_amount,
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
            'age' => 'required|numeric|min:1',
            'weight' => 'required|numeric|min:1',
            'purchased_amount' => 'required|numeric|min:1',
            'sold_amount' => ['nullable','numeric','min:1','required_if:status,3'],
            // 'unique_number' => ['nullable','string','max:20',
            //     Rule::unique('products')->where(function ($query) {
            //         return $query->where('user_id', Auth::id());
            //     })->ignore($request->id), // ignore current category id
            // ],
            
        ], 
        [
            'category.required' => 'Category is required.',
            'gender.required'   => 'Gender is required.',
            'age_type.required' => 'Age Type is required.',
            'age.required'      => 'Age is required.',
            'weight.required'   => 'Weight is required.',
            'purchased_amount.required'   => 'Purchased Amount is required.',
            'sold_amount.required_if' => 'The sold amount is required when status is sold out.',
        ]);

        DB::beginTransaction();

        // In your controller store method
        $product = Product::where('id',$request->id)->first();
        $detail = ProductDetail::where([['product_id',$request->id],['is_delete',0]])->latest('id')->first();

        $product->update([ 
            'status' => $request->status,
            // 'unique_number' => $request->unique_number,
        ]);

        $product_detail = ProductDetail::create([ 
            'product_id' => $product->id,
            'gender_id' => $request->gender,
            'category_id' => $request->category,
            'age_type' => $request->age_type,
            'age' => $request->age,
            'weight' => $request->weight,
            'purchased_amount' => $request->purchased_amount,
            'sold_amount' => $request->sold_amount,
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

        return redirect()->back()->with('success', 'Product updated successfully.');

    }

    public function delete(Request $request,$id)
    {
        $product = Product::where('id',$id)->update(['is_delete'=> 1]);

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function detail_delete(Request $request,$id)
    {
        $product = ProductDetail::where('id',$id)->update(['is_delete'=> 1]);

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function download(Request $request,$id)
    {
        $product = Product::with('details')->findOrFail($id);
        $detail = ProductDetail::where([['product_id', $product->id],['is_delete',0]])->latest('id')->first();

        $details = "Product: " . ($product->unique_number ?? $product->unique_id) . "\n\n"
         . "Category: {$detail->category->name}\n\n"
         . "Age: {$detail->age} {$detail->age_type}\n\n"
         . "Weight: {$detail->weight}\n\n"
         . "Purchased Amount: {$detail->purchased_amount}\n\n"
         . "Sold Amount: " . ($detail->sold_amount ?: '-') . "\n\n"
         . "More: " . url('/products/'.$product->id.'/view');


        $qrCode = \QrCode::size(300)->generate($details);

        //$qrCode = QrCode::size(300)->generate(url('/products/'.$product->id.'/view'));

        return view('products.qrcode', compact('qrCode','product'));
        
    }

    public function download_all(Request $request)
    {
        $products = Product::with('details')->where([['user_id',Auth::id()],['is_delete',0]])->get();

        return view('products.all_qrcode', compact('products'));

    }

    public function download_excel(Request $request, $id = null)
    {
        if($id === null)
        {
            return Excel::download(new ProductsExport, 'products.xlsx');
        }
        else
        {
            return Excel::download(new ProductExport($id), 'product_'.$id.'.xlsx');

        }
        
    }

}
