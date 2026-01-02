<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
  
    use ValidatesRequests;
    private $_rolesEnabled;
    private $_rolesMiddlware;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users', ['except' => ['show', 'showChangePasswordForm', 'changePassword']]);

    }


    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {



           
            return view('dashbord.users.index');
      
            
       
    }

    public function create()
    {



            $Addresses = Address::all();
            $roles = Role::all();

            return view('dashbord.users.create')->with('Addresses', $Addresses)->with('roles', $roles);

       

    }

    public function store(Request $request)
    {
        $messages = [
            'first_name.required' =>trans('users.first_name_R'),
            'last_name.required' => trans('users.last_name_R'),
            'username.required' => trans('users.username_R'),
            'address.required' => trans('users.address_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),
            'role.required' => 'Role is required',

        ];
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50','unique:users'],
            'address' => ['required'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phonenumber' => 'required|digits_between:10,10|numeric|starts_with:091,092,094,021,095,093|unique:users',
            'role' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $user = new User();
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->username = $request->username;

                $user->address_id =decrypt($request->address);
                $user->phonenumber = $request->phonenumber;

                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->active = 1;
                $user->save();

                // Assign role
                $role = Role::findById($request->role);
                $user->assignRole($role);
            
            });

            Alert::success(trans('users.successusersadd'));

            return redirect()->route('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());

            return redirect()->route('users');
        }
    }
     public function users()
    {


        $user = User::with(['address'])->select('*')->orderBy('created_at', 'DESC');
        return datatables()->of($user)
       
            ->addColumn('changeStatus', function ($user) {
                $user_id = encrypt($user->id);

                return '<a href="' . route('users/changeStatus', $user_id) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="#aa6969" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path d="M21 12a9 9 0 1 1-3.51-7.07"/>
  <path d="M22 3v6h-6"/>
</svg>
</a>';
            })
            ->addColumn('edit', function ($user) {
                $user_id = encrypt($user->id);

                return '<a style="color: #f97424;" href="' . route('users/edit', $user_id) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
            })
            ->rawColumns(['changeStatus','edit'])

            ->make(true);
        
    }


    public function edit($id)
    {

            $user_id = decrypt($id);
            $user = User::find($user_id);
            $Addresses = Address::all();
         

            return view('dashbord.users.edit')
          
          ->with('user', $user)->with('Addresses', $Addresses);
       
    }
    public function update(Request $request, $id)
    {

        $user_id = decrypt($id);

        $messages = [
            'first_name.required' =>trans('users.first_name_R'),
            'last_name.required' => trans('users.last_name_R'),
            'username.required' => trans('users.username_R'),
            'address.required' => trans('users.address_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),

        ];
        $this->validate($request, [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:50'],
            'address' => ['required'],
            'phonenumber' => 'required|digits_between:10,10|numeric|starts_with:091,092,094,021,095,093 ',
            'email' => 'required|email|max:50|string|unique:users,email,'.$user_id,

        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->username = $request->username;
                $user->address_id =decrypt($request->address);
                $user->phonenumber = $request->phonenumber;
                $user->email = $request->email;
                $user->active = 1;

                $user->save();

            });
            Alert::success(trans('users.successuseredit'));

            return redirect()->route('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());

            return redirect()->route('users');
        }
    }
    public function show($id)
    {
        $user_id = decrypt($id);
        $user = User::find($user_id);

        return view('dashbord.users.profile')->with('user', $user);
    }


    public function showChangePasswordForm()
    {

        return view('dashbord.users.change_form');
    }

    public function changePassword(Request $request, $id = null)
    {
        $messages = [

            'current-password.required' => trans('users.current-password_r'),
            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' =>trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'current-password' =>['required', 'string', 'min:6'],
            'new-password' =>['required', 'string', 'min:6'],
            'new-password-confirm' =>['required','same:new-password', 'string', 'min:6'],
        ], $messages);
        if (!(Hash::check($request->input('current-password'),Auth::user()->password))) {
            Alert::warning(trans('users.passwordnotmatcheing'));
return redirect()->back()
                 ->with('warning', trans('users.passwordnotmatcheing'));
                        }
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();
        Alert::success(trans('users.changesecc'));
        return redirect()->back()
        ->with('success', trans('users.changesecc'));
}
    public function changeStatus(Request $request, $id)

    {
        $user_id = decrypt($id);
        $user = User::find($user_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                if ($user->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $user->active = $active;
                $user->save();
            });
            Alert::success(trans('users.changestatuesalert'));

            return redirect('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());

            return redirect('users');
        }
    }


  
 
}
