<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Contactus;
use App\Models\exchange;
use App\Models\exchangeitem;
use App\Models\invoice;
use App\Models\invoiceitem;
use App\Models\Returns;
use App\Services\StocksServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnsController extends Controller
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

        return view('dashbord.returns.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('dashbord.returns.create',);
    }


    public function fetchInvoice(Request $request)
    {
        $exchange = invoice::with(['users','customers'])
            ->where('invoice_number', $request->exchangesnumber)
            ->first();

        if (!$exchange) {
            return response()->json(['success' => false]);
        }

        $exchangeItems = invoiceitem::with(['products', 'coolors', 'sizes'])
            ->where('invoices_id', $exchange->id)
            ->get();

        $contactus = Contactus::first();
        $created_at = Carbon::parse($exchange->created_at)->format('Y-m-d H:i');

        $html = view('dashbord.returns.invoice_partial', compact('exchange', 'exchangeItems', 'contactus', 'created_at'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }


    public function processReturn(Request $request)
    {
        try {
            DB::beginTransaction(); // بدء المعاملة
    
            $item = exchangeitem::find($request->item_id);
    
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'العنصر غير موجود']);
            }
    
            // تحديث حالة العنصر ليكون مرتجعًا
            $item->update(['retruned' => true]);
    
            // إنشاء سجل جديد في جدول Returns
            $returns = new Returns();
            $returns->exchanges_id = $item->exchanges_id;
            $returns->grades_id = $item->grades_id;
            $returns->sizes_id = $item->sizes_id;
            $returns->products_id = $item->products_id;
            $returns->price = $item->price;
            $returns->quantty = $item->quantty;
            $returns->users_id = Auth::user()->id;
            $returns->save();
    
            // تحديث المخزون باستخدام StocksServices
            $newStock = new StocksServices();
            $newStock->updatestock(
                $item->quantty,  // الكمية المسترجعة
                $item->grades_id ?? null, // معرف الدرجة
                $item->sizes_id ?? null,  // معرف الموصفات
                $item->products_id // معرف المنتج
            );
    
            DB::commit(); // تأكيد العملية
    
            return response()->json(['success' => true, 'message' => 'تم الإرجاع بنجاح']);
        
        } catch (\Exception $e) {
            DB::rollBack(); // إلغاء التغييرات في حالة حدوث خطأ
            Log::error('خطأ في عملية الإرجاع: ' . $e->getMessage()); // تسجيل الخطأ في السجلات
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء الإرجاع، يرجى المحاولة مرة أخرى']);
        }
    }
    


    public function returns()
    {

        
            $Returns = Returns::with(['grades', 'users','products','sizes', 'invoices'])
            ->orderBy('returns.created_at', 'DESC'); // تحديد الجدول بشكل واض
            return datatables()->of($Returns)
               
                ->make(true);
        
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
    public function show(Returns $returns)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Returns $returns)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Returns $returns)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Returns $returns)
    {
        //
    }
}
