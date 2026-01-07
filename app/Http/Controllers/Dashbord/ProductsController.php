<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Coolor;
use App\Models\Imagesfile;
use App\Models\products;
use App\Models\productsfiles;
use App\Models\Size;
use App\Services\StocksServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\ImageFile;
use RealRashid\SweetAlert\Facades\Alert;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('dashbord.products.index');
    }

    public function products()
    {
        $products = products::with('categories')->get();

        return datatables()->of($products)
            ->addColumn('changeStatus', function ($product) {
                $product_id = encrypt($product->id);
                return '<a href="' . route('products/changeStatus', $product_id) . '">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Refresh icon">
  <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M21 12a9 9 0 1 1-2.64-6.36"/>
    <polyline points="21 3 21 8 16 8"/>
  </g>
</svg>
                        </a>';
            })
            ->addColumn('edit', function ($product) {
                $product_id = encrypt($product->id);
                return '<a href="' . route('products/edit', $product_id) . '">
                              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>

                        </a>';
            })
            ->addColumn('gallery', function ($product) {

                $product_id = encrypt($product->id);
                return '<a href="' . route('products/gellary', $product_id) . '">
<svg class="icon" viewBox="0 0 24 24" fill="none" 
             stroke="currentColor" stroke-width="1.75" 
             stroke-linecap="round" stroke-linejoin="round">
            <!-- إطار المعرض -->
            <rect x="3" y="4" width="18" height="16" rx="2"/>
            <!-- الجبل -->
            <path d="M4 16l5-5 4 4 3-3 4 4"/>
            <!-- الشمس -->
            <circle cx="9" cy="8" r="1.5"/>
        </svg>                        </a>';
            })
            ->addColumn('show', function ($product) {
                $product_id = encrypt($product->id);
                return '<a href="' . route('products/show', $product_id) . '">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                              </svg>
                        </a>';
            })
            ->addColumn('delete', function ($product) {
                $product_id = encrypt($product->id);
                return '<a href="javascript:;" onclick="Swal.fire({
                    title: \'' . trans('product.confirm_delete_product') . '\',
                    icon: \'warning\',
                    showCancelButton: true,
                    confirmButtonText: \'' . trans('product.yes_delete') . '\',
                    cancelButtonText: \'' . trans('product.cancel') . '\'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = \'' . route('products/delete', $product_id) . '\';
                    }
                })">
  <svg class="icon" viewBox="0 0 24 24" fill="none" 
             stroke="currentColor" stroke-width="1.75" 
             stroke-linecap="round" stroke-linejoin="round">
            <!-- غطاء سلة المهملات -->
            <path d="M3 6h18"/>
            <!-- الجسم -->
            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            <rect x="5" y="6" width="14" height="14" rx="2"/>
            <!-- الخطوط الداخلية -->
            <line x1="10" y1="11" x2="10" y2="17"/>
            <line x1="14" y1="11" x2="14" y2="17"/>
        </svg>                </a>';
            })
            ->addColumn('image', function ($product) {
                $imageUrl = asset('images/product/' . $product->image);
                return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" alt="' . $product->name . '" style="max-width: 100px !important;"></a>';
            })

            ->addColumn('coolor', function ($product) {
                $coolors = $product->coolors;
                if ($coolors->isNotEmpty()) {
                    // For each coolor, show as a colored badge; assumes each coolor has a 'color' property.
                    return $coolors->map(function ($coolor) {
                        $color = isset($coolor->namee) ? $coolor->namee : '#666';
                        return '<div style="background-color: ' . $color . '; color: #fff; padding: 16px; border-radius: 3px; margin-right:3px;"></div>';
                    })->implode(' ');
                } else {
                    return 'لايوجد';
                }
            })
            ->addColumn('size', function ($product) {
                // Check if size is not null and decode JSON if it exists
                // Fetch sizes related to this product using the relationship
                $sizes = $product->sizes; // Assuming the `Product` model has a `sizes` relationship method

                if ($sizes->isNotEmpty()) {
                    // If sizes exist, return them as a comma-separated string
                    return $sizes->map(function ($size) {
                        return $size->name; // Assuming the `size` model has a `name` field
                    })->implode(', ');
                } else {
                    // If no sizes are found, return 'لايوجد'
                    return 'لايوجد';
                }
            })

            ->rawColumns(['changeStatus', 'delete', 'edit', 'gallery', 'image', 'coolor', 'show'])
            ->make(true);
    }


    public function gellary($id)
    {
        $products_id = decrypt($id);
        $product = products::findOrFail($products_id);
        $image = Imagesfile::where('products_id', $products_id)->get();

        // If no images, show swal alert and redirect back
        if ($image->isEmpty()) {
            Alert::warning(trans('product.no_images'));
            return redirect()->back();
        }

        return view('dashbord.products.gellary')
            ->with('product', $product)
            ->with('image', $image);
    }

  

    public function deleteImage($id)
{
    $image = Imagesfile::findOrFail($id);

   if (file_exists(public_path('images/product/' . $image->name))) {
            unlink(public_path('images/product/' . $image->name));
        }

    $image->delete();

    return response()->json(['success' => true]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Categories::where('active', 1)->get();
        return view('dashbord.products.create')
            ->with('categories', $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Define custom validation messages
        $messages = [
            'name.required' => trans('product.product_name_required'),
            'namee.required' => trans('product.product_name_english_required'),
            'price.required' => trans('product.price_required'),
            'categories_id.required' => trans('product.category_required'),
            'image.mimes' => trans('product.cover_image_format'),
        ];

        // Validate the incoming request
        $this->validate($request, [
            'categories_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'namee' => 'required|string|max:255',
            'barcode' => 'required|string|max:255',


            'coolor.*' => 'nullable|string|max:255',
            'size.*' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|mimes:jpeg,png,jpg,gif',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
        ], $messages);

        // Use a transaction to ensure data consistency
        try {
            DB::transaction(function () use ($request) {
                // Create a new Product instance
                $product = new products();
                $product->categories_id = $request->categories_id;
                $product->name = $request->name;
                $product->namee = $request->namee;
                $product->barcode = $request->barcode;


                // Product details
                $product->notes = $request->notes;
                
                $product->description_ar = $request->description_ar;
                $product->description_en = $request->description_en;
                $product->price = $request->price;
                $product->is_available = 1; // Default to active product

                $product->brandname_ar = $request->brandname_ar;
                $product->brandname_en = $request->brandname_en;
                $product->country_of_origin_ar = $request->country_of_origin_ar;    
                $product->country_of_origin_en = $request->country_of_origin_en;
                // Handle the main image upload
                if ($request->file('image')) {
                    $fileObject = $request->file('image');
                    $image = "product_" . time()  . ".jpg";
                    $fileObject->move('images/product/', $image);
                    $product->image = $image;
                }

                $product->save();

                // Insert Sizes (if provided)
                if ($request->size) {
                    foreach ($request->size as $size) {
                        if (!empty($size)) { // Check if the size is not empty
                            Size::create([
                                'name' => $size,
                                'products_id' => $product->id
                            ]);
                        } else {
                            // Handle empty size or log an error if necessary
                            // Example:
                            // Log::error("Size is missing.");
                        }
                    }
                }

                // Insert coolors (if provided)
                if ($request->has('coolor')) {
                    $coolors = $request->coolor;
                    foreach ($coolors as $coolor) {
                        Coolor::create([
                            'name' => $coolor,
                            'namee' => $coolor,
                            'products_id' => $product->id,
                        ]);
                    }
                }

                // Insert Additional Images (if provided)
                if ($request->file('images')) {
                    foreach ($request->file('images') as $file) {
                        $filename = "product_img_" . time()  . ".jpg";
                        $file->move('images/product/', $filename);

                        Imagesfile::create([
                            'name' => $filename,
                            'products_id' => $product->id
                        ]);
                    }
                }

                // Add stock after product creation
                $newstoc = new StocksServices();

                // Get coolor IDs and size IDs from the product or request
                $coolors_id = $product->coolors()->pluck('id')->toArray();  // Assuming coolors relation
                $sizes_id = $product->sizes()->pluck('id')->toArray();  // Assuming sizes relation

                // Default quantity (0 if not provided)
                $quantity = $request->has('quantity') ? $request->quantity : 0;

                // If the product has sizes but no coolors
                if (!empty($sizes_id) && empty($coolors_id)) {
                    foreach ($sizes_id as $size_id) {
                        $stock = $newstoc->addstock($quantity, null, $size_id, $product->id);
                    }
                }

                // If product has coolors but no sizes
                elseif (empty($sizes_id) && !empty($coolors_id)) {
                    foreach ($coolors_id as $coolor_id) {
                        $stock = $newstoc->addstock($quantity, $coolor_id, null, $product->id);
                    }
                }

                // If the product has neither sizes nor coolors
                elseif (empty($sizes_id) && empty($coolors_id)) {
                    // No sizes or coolors, add stock for the product
                    $stock = $newstoc->addstock($quantity, null, null, $product->id);
                }

                // If the product has both sizes and coolors
                elseif (!empty($sizes_id) && !empty($coolors_id)) {
                    foreach ($coolors_id as $coolor_id) {
                        foreach ($sizes_id as $size_id) {
                            $stock = $newstoc->addstock($quantity, $coolor_id, $size_id, $product->id);
                        }
                    }
                }
            });

            // Success alert
            Alert::success(trans('product.success_product_add'));
            return redirect()->route('products'); // Redirect to the product creation page or listing page

        } catch (\Exception $e) {
            // Error handling
            Alert::warning(trans('product.error_product_add'), $e->getMessage());
            return back()->withInput();
        }
    }









    protected function convertToString($value)
    {
        // Convert the array to a comma-separated string
        return $value ? implode(',', $value) : '';
    }


    public function changeStatus(Request $request, $id)

    {
        $products_id = decrypt($id);
        $products = products::find($products_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $products_id = decrypt($id);
                $products = products::find($products_id);
                if ($products->is_available == 1) {
                    $is_available = 0;
                } else {
                    $is_available = 1;
                }

                $products->is_available = $is_available;
                $products->save();
            });
            Alert::success(trans('product.status_changed'));

            return redirect('products');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());

            return redirect('products');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $products_id = decrypt($id);
        $product = products::with('categories', 'sizes', 'coolors', 'imagesfiles')->findOrFail($products_id);
        return view('dashbord.products.show')
            ->with('product', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $products_id = decrypt($id);
        $product = products::find($products_id);
        $image = Imagesfile::where('products_id', $products_id)->get();

        $categories = Categories::where('active', 1)->get();
        return view('dashbord.products.edit')
            ->with('product', $product)
            ->with('image', $image)
            ->with('categories', $categories);
    }


    public function destroy($id)
    {
        try {
            // Decrypt the product ID
            $product_id = decrypt($id);

            // Find the product in the database
            $product = products::findOrFail($product_id);

            // Check if the product has an image, and delete it from the server
            if ($product->image && file_exists(public_path('images/product/' . $product->image))) {
                unlink(public_path('images/product/' . $product->image));
            }

            // Delete the product record
            $product->delete();

            // Success alert
            Alert::success(trans('product.product_deleted_success'));

            // Redirect to the products listing page
            return redirect()->route('products');
        } catch (\Exception $e) {
            // Error handling
            Alert::warning("حدث خطأ أثناء حذف المنتج", $e->getMessage());
            return redirect()->route('products');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // تعريف رسائل التحقق المخصصة
        $messages = [
            'name.required' => trans('product.product_name_required'),
            'namee.required' => trans('product.product_name_english_required'),
            'price.required' => trans('product.price_required'),
            'categories_id.required' => trans('product.category_required'),
            'image.mimes' => trans('product.cover_image_format'),
        ];

        // التحقق من البيانات
        $this->validate($request, [
            'categories_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'namee' => 'required|string|max:255',
            'barcode' => 'required|string|max:255',
            'brandname_ar' => 'nullable|string|max:255',
            'brandname_en' => 'nullable|string|max:255',
            'country_of_origin_ar' => 'nullable|string|max:255',
            'country_of_origin_en' => 'nullable|string|max:255',
          
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif',
        ], $messages);

        // بدء عملية التحديث داخل معاملة (Transaction)
        try {
            DB::transaction(function () use ($request, $id) {
                $product_id = decrypt($id);

                $product = products::findOrFail($product_id);

                $product->categories_id = $request->categories_id;
                $product->name = $request->name;
                $product->namee = $request->namee;
                $product->barcode = $request->barcode;
                $product->brandname_ar = $request->brandname_ar;
                $product->brandname_en = $request->brandname_en;
                $product->country_of_origin_ar = $request->country_of_origin_ar;
                $product->country_of_origin_en = $request->country_of_origin_en;
               
                $product->description_ar = $request->description_ar;
                $product->description_en = $request->description_en;
                $product->price = $request->price;
 $product->brandname_ar = $request->brandname_ar;
                $product->brandname_en = $request->brandname_en;
                $product->country_of_origin_ar = $request->country_of_origin_ar;    
                $product->country_of_origin_en = $request->country_of_origin_en;
                // معالجة الصورة إذا تم رفعها
                if ($request->file('image')) {
                    // حذف الصورة القديمة إذا كانت موجودة
                    if ($product->image && file_exists(public_path('images/product/' . $product->image))) {
                        unlink(public_path('images/product/' . $product->image));
                    }

                    // رفع الصورة الجديدة
                    $fileObject = $request->file('image');
                    $image = "category" . time() . ".jpg";
                    $fileObject->move('images/product/', $image);
                    $product->image = $image;
                }

                // Insert Additional Images (if provided)
                if ($request->file('images')) {
                    foreach ($request->file('images') as $file) {
                        $filename = "product_img_" . time()  . ".jpg";
                        $file->move('images/product/', $filename);

                        Imagesfile::create([
                            'name' => $filename,
                            'products_id' => $product->id
                        ]);
                    }
                }


                $product->save();
            });

            // Changed alert message to direct string
            Alert::success(trans('product.product_updated_success'));
            return redirect()->route('products'); // إعادة التوجيه إلى صفحة المنتجات

        } catch (\Exception $e) {
            // معالجة الخطأ
            Alert::warning(trans('product.error_product_update'), $e->getMessage());
            return back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
}
