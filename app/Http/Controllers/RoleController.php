<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use DB;


class RoleController extends Controller
{
   public function index(Request $request)
    {
        // if (auth()->user()->can('role-manage')) {
            if ($request->ajax()) {
                return DataTables::of(Role::query())
                    ->addIndexColumn()
                     ->addColumn('actions', function ($role) {
                        $buttons = '';
                        $editUrl = route('roles.edit', $role->id);
                        $buttons .= '
                                <a href="#" class="btn btn-sm" 
                                data-ajax-popup="true" data-size="md"
                                data-title="Edit Role" data-url="' . $editUrl . '"
                                data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                    <i class="fa fa-edit me-2"></i>
                                </a>
                                ';
                        $deleteUrl = route('roles.destroy', $role->id);
                        $buttons .= '
                                <button type="button" class="btn btn-sm delete-btn"
                                    data-url="' . $deleteUrl . '"
                                    title="Delete">
                                    <i class="fa fa-trash me-2"></i>
                                </button>
                                ';

                        return $buttons;
                    })
                    ->rawColumns(['actions'])
                    ->make(true);
            }
            return view('roles.index');
        // } else {
        //     return redirect()->route('roles.index')->with('error', 'Permission Denied !');
        // }
    }

    public function create()
    {
        // if (auth()->user()->can('role-create')) {
            $permission = Permission::get();
            return view('roles.create', compact('permission'));
        // } else {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function store(Request $request)
    {
        // if (auth()->user()->can('role-create'))

            $validator = Validator::make($request->all(), [
               'name' => 'required|unique:roles,name',
               'permission' => 'required',
               
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $permissionsID = array_map(
                function ($value) {
                    return (int) $value;
                },
                $request->input('permission')
            );

            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        // } else {
        //     return redirect()->route('roles.index')->with('error', 'Permission Denied !');
        // }

    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where('role_has_permissions.role_id', $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        // if (auth()->user()->can('role-edit')) {
            $role = Role::find($id);
            $permission = Permission::get();
            $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all();
            return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
        // } else {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    public function update(Request $request, $id)
    {
        // if (auth()->user()->can('role-edit')) {
          
             $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name,'.$id,
                'permission' => 'required',
               
            ]);

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $permissionsID = array_map(
                function ($value) {
                    return (int) $value;
                },
                $request->input('permission')
            );

            $role->syncPermissions($permissionsID);

            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        // } else {
        //     return redirect()->route('roles.index')->with('error', 'Permission Denied !');
        // }
    }

    public function destroy($id)
    {
        // if(auth()->user()->can('role-delete')){
            $role  = Role::find($id);
            if (is_null($role)) {
                return redirect()->route('roles.index')->with('error', 'Role not found');
            }
            $userCount = \DB::table('model_has_roles')->where('role_id', $id)->count();
            if ($userCount > 0) {
                return redirect()->back()->with('error', 'This Role is assigned to some users.');
            }
            $role->revokePermissionTo(Permission::all());
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        //    }else{
        //     return redirect()->route('roles.index')->with('error', 'Permission Denied !');
        // }
    }

}
