<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\employee;
use App\Models\Coolor;
use App\Models\Item;
use App\Models\products;
use App\Models\Receipt;
use App\Models\Receiptitem;
use App\Models\Size;
use App\Models\Supplier;
use App\Services\StocksServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;

class ReceiptController extends Controller
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

        return view('dashbord.receipts.index');
    }

    /**
     * Show the form for creating a new resource.
     */


    public function create()
    {
        $Items = products::get();
        $suppliers = Supplier::orderBy('name', 'ASC')->get();

        return view('dashbord.receipts.create', compact('Items', 'suppliers'));
    }

    public function getCoolorsAndSizes(Request $request)
    {
        $barcode = $request->barcode;
        $pro = products::where('barcode', $barcode)->first();
    
        if (!$pro) {
        return response()->json(['error' => trans('receipt.product_not_found')], 404);
        }
    
        $product_id = $pro->id;
    
        // جلب الألوان والمقاساتات
        $coolors = Coolor::where('products_id', $product_id)->get();
        $sizes = Size::where('products_id', $product_id)->get();
        $discount = Discount::where('products_id', $product_id)->first();

        if (!$discount) {
            $price = $pro->price; // لا يوجد تخفيض، السعر يبقى كما هو
        } else {
            $price = $pro->price - ($pro->price * ($discount->percentage / 100)); // طرح نسبة التخفيض من السعر الأصلي
        }
        
        return response()->json([
            'coolors' => $coolors->isEmpty() ? [] : $coolors,
            'sizes' => $sizes->isEmpty() ? [] : $sizes,
            'items_id'=>$product_id,
            'name'=>$pro->name,
            'price'=>$price

        ]);
    }
    



    private function calculateTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    public function store(Request $request)
{
    $items = json_decode($request->items, true);

    $request->validate([
        'items' => 'required|json',
        'suppliers_id' => 'required|exists:suppliers,id',
    ]);

    if (!is_array($items) || empty($items)) {
        return redirect()->back()->withErrors(['error' => trans('receipt.empty_products_list')]);
    }
    DB::beginTransaction();
    try {
        $receipt = Receipt::create([
            'total_price' => collect($items)->sum(fn($item) => $item['quantity'] * $item['price']),
            'users_id' => auth()->id(),
            'suppliers_id' => $request->suppliers_id
        ]);


        foreach ($items as $item) {
            // Create a new receipt item

            $receiptItem = Receiptitem::create([
                'receipts_id' => $receipt->id,
                'products_id' => $item['id'],
                'coolors_id' => $item['coolor_id'] ?? null,
                'sizes_id' => $item['size_id'] ?? null,
                'quantty' => $item['quantity'],
                'price' => $item['price'],
                'expired_date' => $item['expired_date'] ?? null
            ]);

            // Update stock for the specific product, coolor, and size
            $newStock = new StocksServices();
            $stock = $newStock->addstock(
                $item['quantity'],  // Quantity to add
                $item['coolor_id'] ?? null, // Coolor ID (if available)
                $item['size_id'] ?? null,  // Size ID (if available)
                $item['id'], // Product ID
                $item['expired_date'] ?? null // Expired date
            );


        }
        

        DB::commit();
        Alert::success(trans('receipt.success_add'));

        return redirect()->route('receipts')->with('success', trans('receipt.added_successfully'));
    } catch (\Exception $e) {
        DB::rollBack();
        Alert::warning($e->getMessage());

        return back()->withErrors(['error' => $e->getMessage()]);
    }
}

    

    public function receipts()
    {


            $receipts = Receipt::with(['users', 'suppliers'])->orderBy('created_at', 'DESC');
            return datatables()->of($receipts)
                ->addColumn('receiptnumber', function ($receipts) {
                    return $receipts->id;
                })
                ->addColumn('showall', function ($receipts) {
                    $receipts_id = encrypt($receipts->id);

                    return '<a href="' . route('receipts/show', $receipts_id) . '"><i  class="fa  fa-file"> </i></a>';
                })

                ->rawColumns(['showall'])
                ->make(true);

    }

    // Function to calculate the total price of all items in the receipt

    /**
     * Display the specified resource.
     */
    public function show($encryptedReceiptId)
    {
        // Decrypt the receipt ID
        $receiptId = decrypt($encryptedReceiptId);

        // Log the activity

        // Fetch the receipt along with its related supplier and user data
        $receipt = Receipt::with(['users', 'suppliers'])->find($receiptId);

        // Fetch all the items related to this receipt
        $receiptItems = Receiptitem::with(['coolors', 'users','products','sizes', 'receipts'])->where('receipts_id', $receipt->id)->get();
        $created_at = Carbon::parse($receipt->created_at)->format('Y-m-d H:i');
        // Pass the receipt and items to the view


        return view('dashbord.receipts.info', [


            'receipt' => $receipt,
            'created_at' => $created_at,
            'receiptItems' => $receiptItems
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        //
    }


    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        //
    }



}
