<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
          /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = Admin::from('admin as a')
            ->leftJoin('notification as n', 'a.id', '=', 'n.user_id')
            ->where('a.role', '>', 0)
            ->select('a.*', 'n.app_version')
            ->groupBy('a.id')
            ->get();
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? with(new \Carbon\Carbon($user->created_at))->format('d-m-Y') : '';
                })
                ->addColumn('actions', function ($user) {
                    $buttons = '';
                    $editUrl = route('user.edit', $user->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="lg"
                            data-title="Edit User" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('user.destroy', $user->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm  delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['created_at','actions'])
                ->make(true);
        }
        return view('users.index');
    }

          /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

          /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

         $validator = Validator::make($request->all(), [
            'business_name' => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'string', 'email', 'max:255', 'unique:admin'],
            'password'      => ['required', 'min:6', 'confirmed'],
            'business_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'note'          => ['nullable', 'string', 'max:255'],
            'mobile'        => ['required', 'string', 'max:15', 'unique:admin'],
            'b_mobile2'     => ['nullable', 'string', 'max:15'],
            'b_email'       => ['nullable', 'email'],
            'b_website'     => ['nullable', 'string', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'gender'        => ['nullable', 'int', 'max:255', 'in:0,1'],
         ]);

        
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        // Store User
        $user                = new Admin();
        $user->business_name = $request->business_name  ?? '';
        $user->email         = $request->email  ?? '';
        $user->password      = bcrypt($request->password);
        $user->note          = $request->note ?? '';
        $user->mobile        = $request->mobile;
        $user->b_mobile2     = $request->b_mobile2  ?? '';
        $user->b_email       = $request->b_email  ?? '';
        $user->b_website     = $request->b_website  ?? '';
        $user->address       = $request->address ?? '';
        $user->gender        = $request->gender;

        // Checkbox Values
        $user->status = $request->has('status') ? 1 : 0;
        $user->ispaid = $request->has('ispaid') ? 1 : 0;

        // Upload Business Logo
        if ($request->hasFile('business_logo')) {
            $path                = $request->file('business_logo')->store('uploads/images/business_logo', 'public');
            $user->business_logo = $path;
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User created successfully!');
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
              //
    }

          /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
              //
    }

          /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
              //
    }
}
