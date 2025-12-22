<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\products;
use App\Models\Stock;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function all()
    {

        return view('dashbord.stock.index');
    }
    public function indexall($id)
    {
        $productsid = decrypt($id);
        $products = products::find($productsid);

        return view('dashbord.stock.indexall')
            ->with('products', $products);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function stock()
    {
        $stock = Stock::leftJoin('products', 'stocks.products_id', '=', 'products.id') // Join products table
            ->leftJoin('coolors', 'stocks.coolors_id', '=', 'coolors.id') // Join grades if applicable
            ->leftJoin('sizes', 'stocks.sizes_id', '=', 'sizes.id') // Join sizes if applicable
            ->select(
                'stocks.products_id',
                'products.barcode as productsbarcode',
                'products.image as productimage', // Include product image
                'products.name as productsname',
                DB::raw('SUM(stocks.quantty) as total_quantity')
            )
            ->groupBy('stocks.products_id', 'products.name', 'products.image', 'products.barcode')
            ->get();

        return datatables()->of($stock)
            ->addColumn('image', function ($stock) {
                return asset('images/product/' . $stock->productimage); // Generate full image path
            })
            ->addColumn('show', function ($stock) {
                $stock_id = encrypt($stock->products_id);
                return '<a  href="' . route('stockall', $stock_id) . '">
 <svg class="icon" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="1.75"
         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <!-- رف علوي + صندوقان -->
      <path d="M3 5h18" />
      <rect x="4.5" y="6.5" width="6" height="5" rx="1"/>
      <rect x="13.5" y="6.5" width="6" height="5" rx="1"/>
      <!-- رف سفلي + صندوق كبير -->
      <path d="M3 14h18" />
      <rect x="7" y="15.5" width="10" height="5" rx="1"/>
      <!-- قواعد الرف -->
      <path d="M3 20.5h18" />
    </svg>                        </a>';
            })
            ->rawColumns(['image', 'show']) // Ensure HTML is rendered correctly
            ->make(true); // Return JSON response
    }

    public function stockall($id)
    {

        $stock = Stock::leftJoin('products', 'stocks.products_id', '=', 'products.id') // Join products table
            ->leftJoin('coolors', 'stocks.coolors_id', '=', 'coolors.id') // Join grades if applicable
            ->leftJoin('sizes', 'stocks.sizes_id', '=', 'sizes.id') // Join sizes if applicable
            ->select(
                'stocks.products_id',
                'products.name as productsname',
                'coolors.name as gradename', // Get grade name
                'sizes.name as sizename',  // Get size name
                DB::raw('SUM(stocks.quantty) as total_quantity')
            )
            ->where('stocks.products_id', $id)
            ->groupBy('stocks.products_id', 'products.name', 'coolors.name', 'sizes.name') // Group by product, grade, and size
            ->get();

        return datatables()->of($stock)->make(true); // Returns the JSON response
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
