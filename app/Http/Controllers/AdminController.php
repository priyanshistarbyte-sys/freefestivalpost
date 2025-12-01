<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Admin::where('role', '!=' ,'3')->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? with(new \Carbon\Carbon($user->created_at))->format('d-m-Y') : '';
                })
               ->editColumn('role', function ($user) {
                    return $user->getRoleNames()->implode(', ');
                })
                ->addColumn('status', function ($user) {
                    $checked = $user->status == 1 ? 'checked' : '';
                    return '
                        <label class="custom-switch">
                            <input type="checkbox" class="status-toggle" data-id="'.$user->id.'" '.$checked.'>
                            <span class="switch-slider"></span>
                        </label>';
                })
                ->addColumn('actions', function ($user) {
                    $buttons = '';
                    $editUrl = route('admin-user.edit', $user->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="lg"
                            data-title="Edit User" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('admin-user.destroy', $user->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm  delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['created_at','role','status','actions'])
                ->make(true);
        }
        return view('admin-user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //  if (auth()->user()->can('user-create')) {
            $roles = Role::pluck('name', 'name')->all();
            return view('admin-user.create', compact('roles'));
        // } else {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         //  if (auth()->user()->can('user-create')) {
            $validator = Validator::make($request->all(), [
                'name'  => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:admin'],
                'mobile' => ['required', 'string', 'max:15', 'unique:admin'],
                'password' => ['required', 'string', 'min:8'],
                'role' => ['required', 'exists:roles,name'],
            ]);

            
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $admin = new Admin();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->mobile = $request->mobile;
            $admin->password = Hash::make($request->password);
            $admin->note = $request->note ?? '';
            $admin->status = $request->status ?? '';
            $admin->save();

            // Assign Role directly 
            $admin->assignRole($request->role);

            return redirect()->route('admin-user.index')->with('success', 'Admin user created successfully.');
        // } else {
        //     return response()->json(['error' => __('Permission denied.')], 401);
        // }
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
    public function edit(string $id)
    {
        $roles = Role::pluck('name', 'name')->all();
        $admin = Admin::findOrFail($id);
        return view('admin-user.edit', compact('roles','admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('admin')->ignore($admin->id)],
            'mobile'   => ['required', 'string', 'max:15', Rule::unique('admin')->ignore($admin->id)],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'exists:roles,name'],

        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->mobile = $request->mobile;
        $admin->note = $request->note ?? '';
        $admin->status = $request->status ?? 0;

        if (!empty($request->password)) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        // Remove old roles & assign new role
        $admin->syncRoles([$request->role]);

        return redirect()->route('admin-user.index')->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admin-user.index')->with('success', 'Admin deleted successfully.');
        
    }
}
