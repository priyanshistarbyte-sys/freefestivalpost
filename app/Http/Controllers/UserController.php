<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
          /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            // $query = Admin::from('admin as a')
            // ->leftJoin('notification as n', 'a.id', '=', 'n.user_id')
            // ->where('a.role', '>', 0)
            // ->select('a.*', 'n.app_version')
            // ->groupBy('a.id')
            // ->get();
            $query = Admin::where('role', 3)->get();
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? with(new \Carbon\Carbon($user->created_at))->format('d-m-Y') : '';
                })
              
                ->addColumn('photo', function ($user) {
                    $imagePath = $user->photo
                        ? asset('storage/' . ltrim($user->photo, '/'))
                        : asset('assets/images/defaultApp.png');
                    return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">';
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
                    $editUrl = route('user.edit', $user->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i> 
                             </a>
                            ';
                    $deleteUrl = route('user.destroy', $user->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['created_at','photo','status','actions'])
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
        $user->role          = 3; // User Role

        // Checkbox Values
        $user->status = $request->has('status') ? 1 : 0;
        $user->ispaid = $request->has('ispaid') ? 1 : 0;

        // Upload Business Logo
        if ($request->hasFile('business_logo')) {
            $path                = $request->file('business_logo')->store('uploads/images/business_logo', 'public');
            $user->photo = $path;
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
        $user = Admin::findOrFail($id);
        return view('users.edit', compact('user'));
    }

          /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Admin::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'business_name' => ['nullable', 'string', 'max:255'],
            'email'         => ['nullable', 'string', 'email', 'max:255', Rule::unique('admin')->ignore($user->id)],
            'business_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'note'          => ['nullable', 'string', 'max:255'],
            'mobile'        => ['required', 'string', 'max:15', Rule::unique('admin')->ignore($user->id)],
            'b_mobile2'     => ['nullable', 'string', 'max:15'],
            'b_email'       => ['nullable', 'email'],
            'b_website'     => ['nullable', 'string', 'max:255'],
            'address'       => ['nullable', 'string', 'max:500'],
            'gender'        => ['nullable', 'int', 'in:0,1'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        // Update User Fields
        $user->business_name = $request->business_name ?? '';
        $user->email         = $request->email ?? '';
        
       
        $user->note      = $request->note ?? '';
        $user->mobile    = $request->mobile;
        $user->b_mobile2 = $request->b_mobile2 ?? '';
        $user->b_email   = $request->b_email ?? '';
        $user->b_website = $request->b_website ?? '';
        $user->address   = $request->address ?? '';
        $user->gender    = $request->gender;
        // Checkbox Values
        $user->status = $request->has('status') ? 1 : 0;
        $user->ispaid = $request->has('ispaid') ? 1 : 0;

        // Upload Business Logo
        if ($request->hasFile('business_logo')) {

            // Delete old logo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Upload new logo
            $path = $request->file('business_logo')->store('uploads/images/business_logo', 'public');
            $user->photo = $path;
        }
        $user->save();
        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
        
    }


    public function updateStatus(Request $request)
    {
        $user = Admin::find($request->id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }

        $user->status = $request->status;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }


    public function feedbackList(Request $request)
    {
         if ($request->ajax()) {
            $query = Feedback::from('feedback as f')
            ->leftJoin('admin as a', 'a.id', '=', 'f.user_id')
            ->select('f.*', 'a.business_name', 'a.mobile')
            ->orderBy('f.id', 'desc')
            ->get();
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('created_at', function ($feedback) {
                    return $feedback->created_at ? with(new \Carbon\Carbon($feedback->created_at))->format('d-m-Y h:m') : '';
                })
                ->addColumn('actions', function ($feedback) {
                    $buttons  = '';
                    $deleteUrl = route('feedback.delete', $feedback->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
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
        return view('users.feedbacklist');
    }

    public function deleteFeedback(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('feedback.list')->with('success', 'Feedback deleted successfully.');
    }

}
