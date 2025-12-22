<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\products;
use Illuminate\Http\Request;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
class DiscountController extends Controller
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

        return view('dashbord.discount.index');
    }


    public function getproudact(Request $request)
    {
        $barcode = $request->barcode;
        $pro = products::where('barcode', $barcode)->first();
    
        if (!$pro) {
            return response()->json(['error' => trans('discount.product_not_found')], 404);
        }
   
    
        return response()->json([
           
            'products'=>$pro
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        return view('dashbord.discount.create');
    }

    public function discount()
    {


            $discount = Discount::with(['products']);
            return datatables()->of($discount)
            ->addColumn('delete', function ($discount) {
                $discount_id = $discount->id;
                return '<button style="background-color: white; border: none;" type="button" class="btn removeItem" data-idss="' . $discount_id . '">

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
        </svg>
                        </button>';
            })
                ->addColumn('discountid', function ($discount) {
                return  $discount->id;
            })
                ->addColumn('price_after_discount', function ($discount) {
                    $originalPrice = $discount->products->price ?? 0;
                    $discountPct = $discount->percentage ?? 0;
                    $discounted = $originalPrice - ($originalPrice * $discountPct / 100);
                    return number_format($discounted, 2);
                })

            ->rawColumns(['delete','discountid'])

                ->make(true);

    }
    public function destroy($id)
    {
        try {
            $Discount = Discount::findOrFail($id);
            $Discount->delete();
            return response()->json(['success' => trans('discount.discount_deleted_success')]);
        } catch (\Exception $e) {
            return response()->json(['error' => trans('discount.error_deleting_discount'), 'message' => $e->getMessage()], 500);
        }
    }


    // public function destroy($id)
    // {
    //     try {
    //         // Decrypt the product ID
    //         $Discount_id = decrypt($id);

    //         // Find the product in the database
    //         $Discount = Discount::findOrFail($Discount_id);

          

    //         // Delete the product record
    //         $Discount->delete();

    //         // Success alert
    //         Alert::success("تم حذف التخفيض بنجاح");

    //         // Redirect to the products listing page
    //         return redirect()->route('discounts');
    //     } catch (\Exception $e) {
    //         // Error handling
    //         Alert::warning("حدث خطأ أثناء حذف التخفيض", $e->getMessage());
    //         return redirect()->route('discounts');
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ✅ التأكد من أن البيانات صحيحة
        $request->validate([
            'products_id' => 'required|string|max:255',
            'percentage' => 'required',

            
        ]);

      
        // التحقق مما إذا كان يوجد تخفيض للمنتج بناءً على معرف المنتج
$discount = Discount::where('products_id', $request->proudatid)->first();

if ($discount) {
    // إذا كان موجودًا، قم بتحديث البيانات
    Alert::error(trans('discount.product_already_discounted'));
        return redirect()->back();
} else {
    // إذا لم يكن موجودًا، قم بإنشاء تخفيض جديد
    try {
        DB::beginTransaction();
        $totalPrice = 0;




        $Discount = Discount::create([
            'percentage' => $request->percentage,
            'products_id' => $request->proudatid
           

            
        ]);

       

        DB::commit();
        Alert::success(trans('discount.discount_added_success'));
        return redirect()->route('discounts');
    } catch (\Exception $e) {
        DB::rollBack();
        Alert::error(trans('discount.error_adding_discount') . $e->getMessage());
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}


       
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
   
}
