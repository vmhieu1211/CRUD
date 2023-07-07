<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role; 

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-create', ['only' => ['create']]);
    }


    public function index(Request $request)
    {
        $permissions = Permission::latest()->paginate(20);
        return view('permissions.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles =Role::get(); 
        $permissions = Permission::get();
        return view('permissions.create', compact('permissions','roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',

        ]);
        $permission = Permission::create(['name' => $request->input('name')]);
        $permission->syncRoles($request->input('roles'));

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    public function edit($id)
    {
        $roles = Role::get();
        $permission = Permission::find($id);
        $permissions = Permission::get();
        $assignedRoles = $permission->roles->pluck('id')->toArray();
        return view('permissions.edit', compact('permission','roles','assignedRoles'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',

        ]);

        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->save();
        $permission->syncRoles($request->input('roles'));
        return redirect()->route('permissions.index')
            ->with('success', 'Permissions updated successfully');
    }

    public function destroy($id)
    {
        Permission::destroy($id);
        return redirect()->route('permissions.index')
            ->with('success', 'Permissions deleted successfully');
    }
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'role_has_permissions', 'permission_id', 'role_id');
    // }
}
