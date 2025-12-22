<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderStatues;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.customer');
    }

    public function dashboard()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
        
        // Get order status IDs
        $orderStatuses = OrderStatues::pluck('id', 'state')->toArray();
        
        // Get order counts by status using order_statues_id
        $orderStatusCounts = [
            'pending' => Order::where('customer_id', $customer->id)
                ->where('order_statues_id', $orderStatuses['قيد الانتظار'] ?? null)->count(),
            'processing' => Order::where('customer_id', $customer->id)
                ->where('order_statues_id', $orderStatuses['قيد التنفيذ'] ?? null)->count(),
            'completed' => Order::where('customer_id', $customer->id)
                ->where('order_statues_id', $orderStatuses['مكتمل'] ?? null)->count(),
            'cancelled' => Order::where('customer_id', $customer->id)
                ->where('order_statues_id', $orderStatuses['ملغي'] ?? null)->count(),
        ];
        
        // Load wishlist data
        $wishlistItems = Wishlist::with('product.category')
            ->where('customer_id', $customer->id)
            ->get();
        $wishlistCount = $wishlistItems->count();
        
        return view('front.customer.dashboard', compact('customer', 'orders', 'wishlistItems', 'wishlistCount', 'orderStatusCounts'));
    }

    public function profile()
    {
        $customer = Auth::guard('customer')->user();
        return view('front.customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;

        if ($request->filled('password')) {
            $customer->password = Hash::make($request->password);
        }

        $customer->save();

        return redirect()->back()->with('success', __('app.profile_updated'));
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)->orderBy('created_at', 'desc')->get();
        return view('front.customer.orders', compact('customer', 'orders'));
    }

    public function showOrder($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('customer_id', $customer->id)->findOrFail($id);
        return view('front.customer.order', compact('customer', 'order'));
    }
}
