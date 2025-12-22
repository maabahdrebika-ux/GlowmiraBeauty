<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage users'); // Only super admin can manage roles
    }

    public function index()
    {
        return view('dashbord.roles.index');
    }

    public function roles()
    {
        $roles = Role::with('permissions')->select('*')->orderBy('created_at', 'DESC');
        return datatables()->of($roles)
            ->addColumn('permissions', function ($role) {
                return $role->permissions->pluck('name')->join(', ');
            })
            ->addColumn('edit', function ($role) {
                $role_id = encrypt($role->id);
                return '<a style="color: #f97424;" href="' . route('roles.edit', $role_id) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="#aa6969">
  <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
</svg>
</a>';
            })
            ->rawColumns(['edit'])
            ->make(true);
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('dashbord.roles.create')->with('permissions', $permissions);
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => trans('roles.name_required'),
            'name.unique' => trans('roles.name_unique'),
        ];
        $this->validate($request, [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ], $messages);
        try {
            DB::transaction(function () use ($request) {
                $role = Role::create(['name' => $request->name]);
                if ($request->permissions) {
                    // Convert permission IDs to permission names
                    $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
                    $role->syncPermissions($permissionNames);
                }
            });
            Alert::success(trans('roles.success_create'));
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('roles.index');
        }
    }

    public function edit($id)
    {
        $role_id = decrypt($id);
        $role = Role::find($role_id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('dashbord.roles.edit')
            ->with('role', $role)
            ->with('permissions', $permissions)
            ->with('rolePermissions', $rolePermissions);
    }

    public function update(Request $request, $id)
    {
        $role_id = decrypt($id);
        $role = Role::find($role_id);
        $messages = [
            'name.required' => trans('roles.name_required'),
            'name.unique' => trans('roles.name_unique'),
        ];
        $this->validate($request, [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ], $messages);
        try {
            DB::transaction(function () use ($request, $role) {
                $role->update(['name' => $request->name]);
                if ($request->permissions) {
                    // Convert permission IDs to permission names
                    $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
                    $role->syncPermissions($permissionNames);
                } else {
                    $role->syncPermissions([]);
                }
            });
            Alert::success(trans('roles.success_update'));
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('roles.index');
        }
    }

    public function destroy($id)
    {
        $role_id = decrypt($id);
        $role = Role::find($role_id);
        try {
            DB::transaction(function () use ($role) {
                $role->delete();
            });
            Alert::success(trans('roles.success_delete'));
            return redirect()->route('roles.index');
        } catch (\Exception $e) {
            Alert::warning($e->getMessage());
            return redirect()->route('roles.index');
        }
    }
}
