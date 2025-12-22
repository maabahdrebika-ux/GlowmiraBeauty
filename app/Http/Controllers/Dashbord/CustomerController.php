<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Only authenticated admin users can access dashboard customer management
        $this->middleware('auth');
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $stats = [
            'total_customers' => Customer::count(),
            'customers_with_orders' => Customer::has('invoices')->count(),
        ];

        return view('dashbord.customers.index', compact('stats'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('dashbord.customers.create');
    }

    /**
     * Store a newly created customer in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'notes' => $request->notes,
                'password' => bcrypt($request->password),
            ]);

            Alert::success('Customer created successfully!');
            return redirect()->route('customers.index');

        } catch (\Exception $e) {
            Alert::error('Error creating customer: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified customer
     */
    public function show($id)
    {
        try {
            $customer = Customer::with('invoices')->findOrFail($id);
            return view('dashbord.customers.show', compact('customer'));
        } catch (\Exception $e) {
            Alert::error('Customer not found');
            return redirect()->route('customers.index');
        }
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return view('dashbord.customers.edit', compact('customer'));
        } catch (\Exception $e) {
            Alert::error('Customer not found');
            return redirect()->route('customers.index');
        }
    }

    /**
     * Update the specified customer in storage
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        try {
            $customer = Customer::findOrFail($id);
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->address = $request->address;
            $customer->notes = $request->notes;

            if ($request->password) {
                $customer->password = bcrypt($request->password);
            }

            $customer->save();

            Alert::success('Customer updated successfully!');
            return redirect()->route('customers.index');

        } catch (\Exception $e) {
            Alert::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified customer from storage
     */
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            // Check if customer has invoices
            if ($customer->invoices()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete customer with existing invoices'
                ], 400);
            }

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting customer: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get customers data for DataTable
     */
    public function datatable(Request $request)
    {
        try {
            // Base query; include invoices count to avoid N+1
            $query = Customer::withCount('invoices')->select([
                'id', 'name', 'email', 'phone', 'address', 'notes'
            ]);

            // DataTables sends search as: search[value]
            $searchValue = $request->input('search.value', null);
            if ($searchValue) {
                $query->where(function ($q) use ($searchValue) {
                    $q->where('name', 'like', "%{$searchValue}%")
                      ->orWhere('email', 'like', "%{$searchValue}%")
                      ->orWhere('phone', 'like', "%{$searchValue}%");
                });
            }

            return DataTables::of($query)
                ->addColumn('customer_name', function ($customer) {
                    return $customer->name ?: '-';
                })
                ->addColumn('customer_email', function ($customer) {
                    return $customer->email ?: '-';
                })
                ->addColumn('customer_phone', function ($customer) {
                    return $customer->phone ?: 'N/A';
                })
                ->addColumn('customer_address', function ($customer) {
                    $address = $customer->address ?? 'N/A';
                    // keep it short in table, full address on hover
                    return '<div style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="' . e($address) . '">' . e($address) . '</div>';
                })
                ->addColumn('invoices_count', function ($customer) {
                    // use the withCount value
                    $count = (int) $customer->invoices_count;
                    if ($count > 0) {
                        return '<span class="badge badge-info"><i class="fa fa-file-invoice"></i> ' . $count . '</span>';
                    }
                    return '<span class="badge badge-secondary">0</span>';
                })
                ->addColumn('customer_notes', function ($customer) {
                    $notes = $customer->notes ?? '';
                    if (trim($notes) === '') {
                        return '<span class="text-muted">No notes</span>';
                    }
                    return '<div style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="' . e($notes) . '">' . e($notes) . '</div>';
                })
                ->addColumn('actions', function ($customer) {
                    $id = $customer->id;

                    // Note: include the removeCustomer class so your JS listener works
                    return '
                        <div class="btn-group" role="group" aria-label="Actions">
                            <a href="' . route('customers.show', $id) . '" style="background:none;border:none;" title="View" aria-label="View customer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969" aria-hidden="true">
                                    <path d="M12 5c-7 0-11 7-11 7s4 7 11 7 11-7 11-7-4-7-11-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                                </svg>
                            </a>

                            <a href="' . route('customers.edit', $id) . '" style="background:none;border:none;margin-left:8px;" title="Edit" aria-label="Edit customer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969" aria-hidden="true">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                            </a>

                        </div>
                    ';
                })
                ->rawColumns(['customer_address', 'invoices_count', 'customer_notes', 'actions'])
                ->make(true);

        } catch (\Exception $e) {
            Log::error('Customer DataTables error: ' . $e->getMessage());
            // return an empty dataset shape so front-end doesn't break
            return DataTables::of(collect([]))->make(true);
        }
    }

    /**
     * Check if customer has invoices (for frontend validation)
     */
    public function checkInvoices($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $hasInvoices = $customer->invoices()->count() > 0;

            return response()->json([
                'has_invoices' => $hasInvoices,
                'message' => $hasInvoices
                    ? __('customers.customer_has_invoices_message')
                    : __('customers.customer_can_be_deleted')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'has_invoices' => false,
                'message' => __('validation.error_occurred')
            ], 500);
        }
    }
}
