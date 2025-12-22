<?php


namespace App\Http\Controllers\Dashbord;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
     
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashbord.address.index');
    }


    public function addresses()
    {
        $Address = Address::orderBy('created_at', 'DESC');
        return datatables()->of($Address)
        ->addColumn('edit', function ($Address) {
            $address_id = encrypt($Address->id);

            return '<a style="color: #f97424;" href="' . route('addresses/edit', $address_id).'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
        })

        ->addColumn('delete', function ($Address) {
            $address_id = encrypt($Address->id);

            return ' <form action="' . route('addresses/delete', $address_id) . '" method="POST">
        <input type="hidden" name="_method" value="DELETE">'
                . csrf_field() .
                '<button type="submit" style="background: none;border: none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" aria-label="Trash">
  <g fill="none" stroke="#C5979A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 6h18"/>
    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
    <path d="M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
    <path d="M10 11v6M14 11v6"/>
  </g>
</svg></button></form>';

        })
    
        ->rawColumns(['edit','delete'])

            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashbord.address.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => trans('address.valirequiredaddress'),
            'nameen.required' => trans('address.valirequiredaddress'),
        ];

        $this->validate($request, [
            'name' => ['required', 'string', 'max:50', 'unique:addresses'],
            'nameen' => ['required', 'string', 'max:50', 'unique:addresses'],
        ], $messages);
        
        try {
            DB::transaction(function () use ($request) {
                $address = new Address();
                $address->name = $request->name;
                $address->nameen = $request->nameen;
                $address->save();
            });
            
            Alert::success(trans('address.successaddressadd'));
            return redirect()->route('addresses');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('addresses');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($address)
    { 
        $address_id = decrypt($address);
        $address = Address::find($address_id);
        return view('dashbord.address.edit')->with('address', $address);
    }
    
    public function delete($id)
    {
        $id = decrypt($id);
        $Address = Address::find($id);
        $Address->delete();
        Alert::success(trans('address.successaddressdelete'));
        return redirect()->back();
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $address)
    {
        $address_id = decrypt($address);
      
        $messages = [
            'name.required' => trans('address.valirequiredaddress'),
            'nameen.required' => trans('address.valirequiredaddress'),
        ];

        $this->validate($request, [
            'name' => ['required', 'string', 'max:50'],
            'nameen' => ['required', 'string', 'max:50'],
        ], $messages);
        
        try {
            DB::transaction(function () use ($request, $address) {
                $address_id = decrypt($address);
                $addr = Address::find($address_id);
                $addr->name = $request->name;
                $addr->nameen = $request->nameen;
                $addr->save();
            });

            Alert::success(trans('address.successaddressedit'));
            return redirect()->route('addresses');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('addresses');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        //
    }
}
