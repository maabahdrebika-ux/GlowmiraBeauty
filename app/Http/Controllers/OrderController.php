<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\Orderitems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Schema;


class OrderController extends Controller
{
     public function __construct()
    {
        // Only authenticated customers can add reviews
        $this->middleware('auth.customer');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'username.required'     => app()->getLocale() == 'ar' ? 'يرجى إدخال اسم المستخدم' : 'Please enter the username',
            'phonenumber.required'  => app()->getLocale() == 'ar' ? 'يرجى إدخال رقم الهاتف' : 'Please enter the phone number',
            'email.required'        => app()->getLocale() == 'ar' ? 'يرجى إدخال البريد الإلكتروني' : 'Please enter the email',
            'email.email'           => app()->getLocale() == 'ar' ? 'يرجى إدخال بريد إلكتروني صحيح' : 'Please enter a valid email',
            'address_id.required'   => app()->getLocale() == 'ar' ? 'يرجى اختيار العنوان' : 'Please select an address',
            'address_id.exists'     => app()->getLocale() == 'ar' ? 'العنوان المحدد غير صحيح' : 'The selected address is invalid',
        ];

        $validated = $request->validate([
            'username'      => 'required|string|max:255',
            'phonenumber'   => 'required|string|max:20',
            'email'         => 'required|email|max:255',
            'address_id'    => 'required',
            'note'          => 'nullable|string|max:500',
        ], $messages);

        // Retrieve cart from session
        $cart = session('cart', []);
    

        if (empty($cart)) {
            return redirect()->back()->withErrors(['cart' => __('The cart must contain products')]);
        }

        DB::beginTransaction();
        try {
            // حساب إجمالي الطلب
            $total = array_reduce($cart, function ($carry, $item) {
                $discountedPrice = $item['price'] - $item['discount'];
                return $carry + ($discountedPrice * $item['quantity']);
            }, 0);


            // Get selected address
          
            
            // Get the authenticated customer
            $customer = auth('customer')->user();
            
            // Update customer profile with new information
            if ($customer) {
                DB::table('customers')->where('id', $customer->id)->update([
                    'name' => $validated['username'],
                    'phone' => $validated['phonenumber'],
                    'email' => $validated['email'],
                    'address' => $validated['address_id'],
                ]);
            }
            
            // Create the order
            $order = Order::create([
               
                'total_price' => $total,
                'order_statues_id' => 1,
                'customer_id' => Auth::guard('customer')->id(),
                'note' => $validated['note'] ?? null,
            ]);



            // إضافة عناصر الطلب من السلة
            foreach ($cart as $item) {

                Orderitems::create([
                    'orders_id'   => $order->id,
                    'products_id' => $item['product_id'],
                    'price'       => $item['price'],
                    'coolors_id'  => isset($item['grades_id']) ? $item['grades_id'] : null, // if product has no color, null is saved
                    'sizes_id'    => isset($item['sizes_id']) ? $item['sizes_id'] : null,  // if product has no size, null is saved
                    'quantty'     => $item['quantity'],

                ]);
            }

            // إضافة إشعار للطلب الجديد
            DB::table('notifications')->insert([
                'orders_id'  => $order->id,
                'url'=>'pending/oreder',
                'message'    => __('لديك طلب جديد تحت رقم: ORD - ') . $order->id,
                'messageen'  => __('You have a new request under number: ORD - ') . $order->id,
                'created_at' => now(),

            ]);

            session()->forget('cart');

            DB::commit();

            // Redirect to the success page and pass the order id
            return redirect()->route('order/success')->with('order_id', $order->id);
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error($e->getMessage());
            return redirect()->back()->withErrors(['error' => __('products.error_occurred')]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    /**
     * Display the order success page.
     */
    public function success()
    {
        $orderId = session('order_id');
        $order=Order::find($orderId);
        return view('front.order-success', compact('order'));
    }

    /**
     * Remove an item from the order.
     */

    /**
     * Mark the order as completed.
     */
   
}
