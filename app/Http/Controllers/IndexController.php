<?php

namespace App\Http\Controllers;

use App\Models\Aboutus;
use App\Models\Blog;
use App\Models\Categories;
use App\Models\Contactus;
use App\Models\Discount;
use App\Models\Coolor;
use App\Models\Inbox;
use App\Models\Policy;
use App\Models\products;
use App\Models\Salesbanner;
use App\Models\Size;
use App\Models\Slider;
use App\Models\Stock;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Check if user is already logged in and redirect to dashboard
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        // Handle language switching
        if ($request->has('lang')) {
            $lang = $request->input('lang');
            if (in_array($lang, ['en', 'ar'])) {
                app()->setLocale($lang);
                session(['locale' => $lang]);
            }
        }

        $about = Aboutus::find(1);
        $slider = Slider::get();

        // Get categories for the tab navigation
        $categories = Categories::where('active', 1)->get();

        // Get new arrival products (limit to 8 for the slider)
        $newArrivalProducts = products::with('categories', 'discounts', 'stocks')
            ->where('is_available', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $categoriess = categories::take(4)->get();
        $productscategories = Stock::with('products')->get();
        $discount = Discount::all(); // updated to fetch all records
        $sizes = Size::all();         // updated to fetch all records
        $coolors = Coolor::all();       // updated to fetch all records
        $colors = DB::table('stocks')
        ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
        ->select('coolors.id', 'coolors.name')
        ->distinct()
        ->paginate(12)
        ->appends(request()->all());
        return view('front.index')
        ->with('discount', $discount)
        ->with('sizes', $sizes)
        ->with('coolors', $coolors)
        ->with('colors', $colors)
        ->with('categories', $categories) // Add categories for tab navigation
        ->with('newArrivalProducts', $newArrivalProducts) // Add new arrival products
        ->with('productscategories', $productscategories)
        ->with('categoriess', $categoriess)
        ->with('slider', $slider)
        ->with('about', $about);
    }


    public function discountprod(Request $request)
    {
        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');
            $products = products::with('categories')
                ->where('is_available', 1)
                ->where('categories_id', $categoryId)
                ->whereHas('discounts')
                ->latest()
                ->paginate(12)
                ->appends($request->all());
        } elseif ($request->has('size_id')) {
            $sizeId = $request->input('size_id');
            $stocks = Stock::with('products', 'products.categories')
                ->where('sizes_id', $sizeId)
                ->whereHas('products', function ($query) {
                    $query->where('is_available', 1)
                          ->whereHas('discounts');
                })
                ->paginate(12);
            $stocks->setCollection(
                $stocks->getCollection()->map(function ($stock) {
                    return $stock->products; // Extract the product for consistency
                })
            );
            $stocks->appends($request->all());
            $products = $stocks;
        } elseif ($request->has('coolors_id')) {
            $coolorId = $request->input('coolors_id');
            $stocks = Stock::with('products', 'products.categories')
                ->where('coolors_id', $coolorId)
                ->whereHas('products', function ($query) {
                    $query->where('is_available', 1)
                          ->whereHas('discounts');
                })
                ->paginate(12);
            $stocks->setCollection(
                $stocks->getCollection()->map(function ($stock) {
                    return $stock->products; // Extract the product for consistency
                })
            );
            $stocks->appends($request->all());
            $products = $stocks;
        } elseif ($request->has('brand')) {
            $brandName = $request->input('brand');
            $products = products::with('categories')
                ->where('is_available', 1)
                ->where('material', $brandName)
                ->whereHas('discounts')
                ->latest()
                ->paginate(12)
                ->appends($request->all());
        } elseif ($request->has('sort')) {
            $sort = $request->input('sort');
            if ($sort === 'priceHighToLow') {
                $products = products::with('categories')
                    ->where('is_available', 1)
                    ->whereHas('discounts')
                    ->orderBy('price', 'desc')
                    ->paginate(12)
                    ->appends($request->all());
            } elseif ($sort === 'priceLowToHigh') {
                $products = products::with('categories')
                    ->where('is_available', 1)
                    ->whereHas('discounts')
                    ->orderBy('price', 'asc')
                    ->paginate(12)
                    ->appends($request->all());
            } else {
                $products = products::with('categories')
                    ->where('is_available', 1)
                    ->whereHas('discounts')
                    ->latest()
                    ->paginate(12)
                    ->appends($request->all());
            }
        } else {
            $products = products::with('categories')
                ->where('is_available', 1)
                ->whereHas('discounts')
                ->latest()
                ->paginate(12)
                ->appends($request->all());
        }

        $minProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->min('products.price');
        $maxProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->max('products.price');

        $sizes = DB::table('stocks')
            ->join('sizes', 'stocks.sizes_id', '=', 'sizes.id')
            ->select('sizes.id', 'sizes.name')
            ->distinct()
            ->paginate(12)
            ->appends($request->all());

        $colors = DB::table('stocks')
            ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
            ->select('coolors.id', 'coolors.name')
            ->distinct()
            ->get();


        $categories = Categories::where('active', 1)
            ->withCount(['products' => function ($query) {
                $query->where('is_available', 1)
                      ->whereHas('discounts');
            }])
            ->get();
           

        $categoriesproducts = products::where('is_available', 1)
            ->whereHas('discounts')
            ->count();

        $brands = DB::table('products')
            ->select('products.material as brandname')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('discounts')
                      ->whereRaw('discounts.products_id = products.id');
            })
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->groupBy('products.material')
            ->get();

        $discount = Discount::all(); // updated to fetch all records
        $sizes = Size::all();         // updated to fetch all records
        $coolors = Coolor::all();       // updated to fetch all records

        return view('front.discountprod')
            ->with('products', $products)
            ->with('discount', $discount)
            ->with('sizes', $sizes)
            ->with('coolors', $coolors)
            ->with('minProductPrice', $minProductPrice)
            ->with('maxProductPrice', $maxProductPrice)
            ->with('sizes', $sizes)
            ->with('colors', $colors)
            ->with('categories', $categories)
            ->with('brands', $brands)
            ->with('categoriesproducts', $categoriesproducts);
    }


    public function productcategory($id, Request $request)
    {
        try {
            // Try to decrypt the ID first
            $categoryId = decrypt($id);
        } catch (\Exception $e) {
            // If decryption fails, check if it's a valid integer
            if (is_numeric($id) && intval($id) > 0) {
                $categoryId = intval($id);
            } else {
                // If both fail, redirect back with error
                Alert::error(__('menu.no_products'));
                return redirect()->route('/');
            }
        }
    
        $categoriess=Categories::find($categoryId);
      
        $query = products::with('categories', 'stocks', 'discounts')
            ->where('is_available', 1)
            ->where('categories_id', $categoryId);

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('namee', 'like', '%' . $search . '%');
            });
        }

        // Apply size filter
        if ($request->has('size_id') && !empty($request->size_id)) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('sizes_id', $request->size_id);
            });
        }

        // Apply color/grade filter
        if ($request->has('grades_id') && !empty($request->grades_id)) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('coolors_id', $request->grades_id);
            });
        }

        // Apply brand filter
        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('material', $request->brand);
        }

        // Apply price range filter
        if ($request->has('price_range') && !empty($request->price_range)) {
            $range = $request->price_range;
            if ($range === '0-25') {
                $query->whereBetween('price', [0, 25]);
            } elseif ($range === '25-50') {
                $query->whereBetween('price', [25, 50]);
            } elseif ($range === '50-75') {
                $query->whereBetween('price', [50, 75]);
            } elseif ($range === '75-100') {
                $query->whereBetween('price', [75, 100]);
            } elseif ($range === '100+') {
                $query->where('price', '>=', 100);
            }
        }

        // Apply country of origin filter
        if ($request->has('country') && !empty($request->country)) {
            $countryField = 'country_of_origin_' . (app()->getLocale() == 'ar' ? 'ar' : 'en');
            $query->where($countryField, $request->country);
        }

        // Apply sorting
        if ($request->has('sort') && !empty($request->sort)) {
            $sort = $request->sort;
            if ($sort === 'priceHighToLow') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'priceLowToHigh') {
                $query->orderBy('price', 'asc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends(request()->all());
    
        $minProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->min('products.price');
        $maxProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->max('products.price');
    
        $sizes = DB::table('stocks')
            ->join('sizes', 'stocks.sizes_id', '=', 'sizes.id')
            ->select('sizes.id', 'sizes.name')
            ->distinct()
            ->get();

    
        $colors = DB::table('stocks')
            ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
            ->select('coolors.id', 'coolors.name')
            ->distinct()
            ->get();

    
        $categories = Categories::where('active', 1)
            ->withCount(['products' => function ($query) {
                $query->where('is_available', 1);
            }])
            ->get();

    
        // Filter brands based on the selected category
        $brands = DB::table('products')
            ->where('categories_id', $categoryId)
            ->select('products.material')
            ->groupBy('products.material')
        ->get();
    
        $discount = Discount::all(); // updated to fetch all records
        $sizes = Size::all();         // updated to fetch all records
        $coolors = Coolor::all();       // updated to fetch all records
    
        return view('front.productcategory')
        ->with('categoriess', $categoriess)

        
            ->with('id', $id)
            ->with('products', $products)
            ->with('discount', $discount)
            ->with('sizes', $sizes)
            ->with('coolors', $coolors)
            ->with('minProductPrice', $minProductPrice)
            ->with('maxProductPrice', $maxProductPrice)
            ->with('colors', $colors)
            ->with('categories', $categories)
            ->with('brands', $brands);
    }



    
    public function products(Request $request)
    {
        $query = products::with('categories', 'imagesfiles', 'stocks', 'discounts')
            ->where('is_available', 1);

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('namee', 'like', '%' . $search . '%');
            });
        }

        // Apply category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('categories_id', $request->category_id);
        }

        // Apply size filter
        if ($request->has('size_id') && !empty($request->size_id)) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('sizes_id', $request->size_id);
            });
        }

        // Apply color/grade filter
        if ($request->has('grades_id') && !empty($request->grades_id)) {
            $query->whereHas('stocks', function ($q) use ($request) {
                $q->where('coolors_id', $request->grades_id);
            });
        }

        // Apply brand filter
        if ($request->has('brand') && !empty($request->brand)) {
            $query->where('material', $request->brand);
        }

        // Apply price range filter
        if ($request->has('price_range') && !empty($request->price_range)) {
            $range = $request->price_range;
            if ($range === '0-25') {
                $query->whereBetween('price', [0, 25]);
            } elseif ($range === '25-50') {
                $query->whereBetween('price', [25, 50]);
            } elseif ($range === '50-75') {
                $query->whereBetween('price', [50, 75]);
            } elseif ($range === '75-100') {
                $query->whereBetween('price', [75, 100]);
            } elseif ($range === '100+') {
                $query->where('price', '>=', 100);
            }
        }

        // Apply country of origin filter
        if ($request->has('country') && !empty($request->country)) {
            $countryField = 'country_of_origin_' . (app()->getLocale() == 'ar' ? 'ar' : 'en');
            $query->where($countryField, $request->country);
        }

        // Apply sorting
        if ($request->has('sort') && !empty($request->sort)) {
            $sort = $request->sort;
            if ($sort === 'priceHighToLow') {
                $query->orderBy('price', 'desc');
            } elseif ($sort === 'priceLowToHigh') {
                $query->orderBy('price', 'asc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends($request->all());

        $minProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->min('products.price');
        $maxProductPrice = DB::table('stocks')
            ->join('products', 'stocks.products_id', '=', 'products.id')
            ->max('products.price');

        $sizes = DB::table('stocks')
            ->join('sizes', 'stocks.sizes_id', '=', 'sizes.id')
            ->select('sizes.id', 'sizes.name')
            ->distinct()
            ->get();


        $colors = DB::table('stocks')
            ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
            ->select('coolors.id', 'coolors.name')
            ->distinct()
            ->get();


        $categories = Categories::where('active', 1)
            ->withCount(['products' => function ($query) {
                $query->where('is_available', 1);
            }])
            ->get();


        $categoriesproducts = products::where('is_available', 1)->count();

        $brands = DB::table('products')
            ->select('products.material as brandname')
            ->whereNotNull('material')
            ->where('material', '!=', '')
            ->groupBy('products.material')
            ->get();


        $discount = Discount::all(); // updated to fetch all records
        $sizes = Size::all();         // updated to fetch all records
        $coolors = Coolor::all();       // updated to fetch all records

        // Load relationships for sizes and grades per product
        $products->load('stocks.sizes', 'stocks.coolors');

        $sizesByProduct = $products->getCollection()->mapWithKeys(function ($product) {
            return [$product->id => $product->stocks->pluck('sizes')->filter()->unique()];
        });

        $gradesByProduct = $products->getCollection()->mapWithKeys(function ($product) {
            return [$product->id => $product->stocks->pluck('coolors')->filter()->unique()];
        });

        return view('front.products')
            ->with('products', $products)
            ->with('discount', $discount)
            ->with('sizesFilter', $sizes)
            ->with('coolors', $coolors)
            ->with('minProductPrice', $minProductPrice)
            ->with('maxProductPrice', $maxProductPrice)
            ->with('sizes', $sizes)
            ->with('colors', $colors)
            ->with('categories', $categories)
            ->with('brands', $brands)
            ->with('categoriesproducts', $categoriesproducts)
            ->with('sizesByProduct', $sizesByProduct)
            ->with('gradesByProduct', $gradesByProduct);
    }

    public function proudactinfo($id)
    {
        try {
            // Try to decrypt the ID first (like other methods in this controller)
            $productId = decrypt($id);
        } catch (\Exception $e) {
            // If decryption fails, check if it's a valid integer
            if (is_numeric($id) && intval($id) > 0) {
                $productId = intval($id);
            } else {
                // If both fail, redirect back with error
                Alert::error(__('menu.no_products'));
                return redirect()->route('/');
            }
        }

        $product = products::with(['categories', 'discounts', 'stocks.sizes', 'stocks.coolors'])
            ->find($productId);

        // Check if product exists
        if (!$product) {
            Alert::error(__('menu.no_products'));
            return redirect()->route('/');
        }

        $images = DB::table('imagesfiles')
            ->where('products_id', $productId)
            ->paginate(12)
            ->appends(request()->all());

        $discount = $product->discounts->first();
        $discountedPrice = $discount
            ? $product->price - ($product->price * $discount->percentage) / 100
            : null;

        $products = products::with('categories')
            ->where('id','!=',$product->id)
            ->where('categories_id',$product->categories_id)
            ->where('is_available', 1)
            ->latest()
            ->paginate(12)
            ->appends(request()->all());

        $allDiscounts = Discount::all(); // updated to fetch all records
        $sizes = Size::all();         // updated to fetch all records
        $coolors = Coolor::all();       // updated to fetch all records
        $colors = DB::table('stocks')
            ->join('coolors', 'stocks.coolors_id', '=', 'coolors.id')
            ->select('coolors.id', 'coolors.name')
            ->distinct()
            ->paginate(12)
            ->appends(request()->all());
        
        // Load product reviews with customer information and replies
        // Show all reviews (including unapproved) for demo purposes
        // To show only approved reviews, add: ->where('is_approved', true)
        $reviews = Review::with(['customer', 'replies.customer', 'replies.admin'])
            ->where('products_id', $productId)
            ->latest()
            ->paginate(10)
            ->appends(request()->all());
        
        // Get review statistics (only approved for statistics)
        $averageRating = Review::getAverageRating($productId);
        $reviewCount = Review::getReviewCount($productId);
        $ratingDistribution = Review::getRatingDistribution($productId);
        
        // Debug: Log the product image path
        \Illuminate\Support\Facades\Log::info('Product image path: ' . ($product->image ?? 'No image'));
        \Illuminate\Support\Facades\Log::info('Current locale: ' . app()->getLocale());
        \Illuminate\Support\Facades\Log::info('Asset URL: ' . asset('images/product/' . ($product->image ?? 'default.jpg')));
        
        return view('front.product-detail')
            ->with('products', $products)
            ->with('colors', $colors)
            ->with('discount', $discount)
            ->with('sizes', $sizes)
            ->with('coolors', $coolors)
            ->with('product', $product)
            ->with('imagesfiles', $images)
            ->with('discountedPrice', $discountedPrice)
            ->with('reviews', $reviews)
            ->with('averageRating', round($averageRating, 1))
            ->with('reviewCount', $reviewCount)
            ->with('ratingDistribution', $ratingDistribution);
    }


    
     public function policies()
    {
        $policies = Policy::first();
        return view('front.policies')
            ->with('policies', $policies);
    }
    
    public function about()
    {
      $ab = Aboutus::first();
        return view('front.about')
            ->with('ab', $ab);
    }


    public function contacts()
    {
        // Use first() to get a single record instead of paginate()
        $contact = Contactus::first();
        // Get map location from the database columns "lan" and "long"
        $mapLocation = [
            'lat' => $contact->lan,
            'lng' => $contact->long
        ];
        return view('front.contact')
            ->with('contact', $contact)
            ->with('mapLocation', $mapLocation); // new variable for map
    }

    public function categories()
    {
        $categories = Categories::where('active', 1)->paginate(12)->appends(request()->all());
        return view('front.categories')
            ->with('categories', $categories);
    }


    public function send(Request $request)
    {
        // Custom validation messages
        $messages = [
            'name.required' => __('validation.name_required'),
            'message.required' => __('validation.message_required'),
            'subject.required' => __('validation.subject_required'),
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'email.dns' => __('validation.email_dns_invalid'),
        ];

        // Validation rules
        $this->validate($request, [
            'name' => 'required',
            'message' => 'required',
            'subject' => 'required',
            'email' => 'required|email:rfc,dns',
        ], $messages);

        try {
            // Database transaction
            DB::transaction(function () use ($request) {
                $Inbox = new Inbox();
                $Inbox->name = $request->name;
                $Inbox->message = $request->message;
                $Inbox->subject = $request->subject;
                $Inbox->email = $request->email;
                $Inbox->save();
            });

            Alert::success(__('messages.success_message'));
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::warning(__('messages.failure_message', ['error' => $e->getMessage()]));
            return redirect()->back();
        }
    }


    public function menu()
    {

        $Categories = Categories::where('active', 1)->paginate(12)->appends(request()->all());
        return view('menu')->with('Categories', $Categories);
    }

    public function menuinfo($id)
    {

        $cat = decrypt($id);

        $Categories = Categories::find($cat);
        $products = products::where('categories_id', $cat)->paginate(12)->appends(request()->all());
        return view('menuinfo')
            ->with('products', $products)
            ->with('Categories', $Categories)
        ;
    }


    public function blog()
    {
        $blogs = Blog::paginate(10);
        $popularPosts = Blog::orderBy('created_at', 'desc')->limit(4)->get();
        return view('front.blog')
            ->with('blogs', $blogs)
            ->with('popularPosts', $popularPosts);
    }

    public function blogDetail($id)
    {
        $blog = Blog::findOrFail($id);
        return view('front.blog-detail')->with('blog', $blog);
    }
}
