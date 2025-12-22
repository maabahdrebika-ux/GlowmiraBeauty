<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
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
        return view('dashbord.suppliers.index');
    }

    /**
     * Get suppliers data for DataTables
     */
    public function getSuppliers()
    {
        $suppliers = Supplier::orderBy('created_at', 'DESC');
        
        return datatables()->of($suppliers)
            ->addColumn('actions', function ($supplier) {
                $supplierId = encrypt($supplier->id);

                $editBtn = '<a style="color: #f97424;" href="' . route('suppliers/edit', $supplierId) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';


                return $editBtn ;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashbord.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            Supplier::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            Alert::success(trans('supplier.added_successfully'), trans('supplier.supplier_added_successfully'));
            return redirect()->route('suppliers');
        } catch (\Exception $e) {
            Alert::error(trans('supplier.error'), trans('supplier.error_adding_supplier') . ': ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            $supplier = Supplier::findOrFail($id);
            return view('dashbord.suppliers.edit', compact('supplier'));
        } catch (\Exception $e) {
            Alert::error(trans('supplier.error'), trans('supplier.supplier_not_found'));
            return redirect()->route('suppliers');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $id = decrypt($encryptedId);
            $supplier = Supplier::findOrFail($id);
            
            $supplier->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'notes' => $request->notes,
            ]);

            Alert::success(trans('supplier.updated_successfully'), trans('supplier.supplier_updated_successfully'));
            return redirect()->route('suppliers');
        } catch (\Exception $e) {
            Alert::error(trans('supplier.error'), trans('supplier.error_updating_supplier') . ': ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            Alert::success(trans('supplier.deleted_successfully'), trans('supplier.supplier_deleted_successfully'));
            return redirect()->route('suppliers');
        } catch (\Exception $e) {
            Alert::error(trans('supplier.error'), trans('supplier.error_deleting_supplier') . ': ' . $e->getMessage());
            return back();
        }
    }
}
