<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
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
                ->where('a.role', 'User')
                ->select('a.*', 'n.app_version');
            
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }
            
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }
            
            if ($request->filled('type')) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('post', function ($user) {
                    return $user->countUserPostTotal($user->id);
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at
                        ? \Carbon\Carbon::parse($user->created_at)->format('d-m-Y h:i A')
                        : '';
                })
                ->addColumn('photo', function ($user) {
                    $imagePath = $user->photo ? asset('storage/' . ltrim($user->photo, '/')) : null;
                    if (!empty($user->photo)) {
                        return '
                            <a class="image-popup-no-margins" href="' . $imagePath . '">
                                <img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">
                            </a>
                            ';
                    } else {
                        return 'No Logo';
                    }
                })

               ->editColumn('mobile', function ($user) {
                    return '<a target="_blank" style="text-decoration: underline; color: #0088cc"
                                href="https://web.whatsapp.com/send?phone=91' . $user->mobile . '">
                                ' . $user->mobile . '
                            </a>';
                })
                ->editColumn('ispaid', function ($user) {
                    $ispaidTitle = $this->userPaidStatus($user->ispaid, $user->planStatus);

                    if ($user->ispaid == 0) {
                        return '<i class="fa fa-times-circle iconfsize icolred"
                                    data-toggle="tooltip" title="' . $ispaidTitle . '"></i>
                                <span class="ms-1">' . $ispaidTitle . '</span>';
                    }
                    return '<i class="fa fa-check-circle iconfsize icolgreen"
                                data-toggle="tooltip" title="' . $ispaidTitle . '"></i>
                            <span class="ms-1">' . $ispaidTitle . '</span>';
                })

                ->addColumn('status', function ($user) {
                    $checked = $user->status == 1 ? 'checked' : '';
                    return '
                        <label class="custom-switch">
                            <input type="checkbox" class="status-toggle"
                                data-id="' . $user->id . '" ' . $checked . '>
                            <span class="switch-slider"></span>
                        </label>';
                })
                ->editColumn('expdate', function ($user) {
                    return $user->expdate
                        ? \Carbon\Carbon::parse($user->expdate)->format('d/m/Y')
                        : '';
                })

                ->addColumn('actions', function ($user) {
                    return '
                        <a href="' . route('user.changePassword', $user->id) . '" class="btn btn-sm">
                            <i class="fa fa-key me-2"></i>
                        </a>
                        <a href="' . route('user.customframe', $user->id) . '" class="btn btn-sm">
                            <i class="fa fa-eye me-2"></i>
                        </a>
                        <a href="' . route('user.edit', $user->id) . '" class="btn btn-sm">
                            <i class="fa fa-edit me-2"></i>
                        </a>
                        <button class="btn btn-sm delete-btn"
                            data-url="' . route('user.destroy', $user->id) . '">
                            <i class="fa fa-trash me-2"></i>
                        </button>
                    ';
                })

                ->rawColumns(['post','created_at','photo','mobile','ispaid','status','expdate','actions'])
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
        $user->email         = $request->email ?: null;
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
        $user->email         = $request->email ?: null;
        $user->note          = $request->note ?? '';
        $user->mobile        = $request->mobile;
        $user->b_mobile2     = $request->b_mobile2 ?? '';
        $user->b_email       = $request->b_email ?? '';
        $user->b_website     = $request->b_website ?? '';
        $user->address       = $request->address ?? '';
        $user->gender        = $request->gender;
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
        // Delete image
        if ($user->business_logo && Storage::disk('public')->exists($user->business_logo)) {
            Storage::disk('public')->delete($user->business_logo);
        }
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

    public function changePassword($id)
    {
        $user = Admin::findOrFail($id);
        return view('users.change-password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Admin::findOrFail($id);
        // Update new password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function feedbackList(Request $request)
    {
        if ($request->ajax()) {
            $query = Feedback::from('feedback as f')
                ->leftJoin('admin as a', 'a.id', '=', 'f.user_id')
                ->select('f.*', 'a.business_name', 'a.mobile')
                ->orderByRaw('f.id DESC')
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
                ->rawColumns(['created_at', 'actions'])
                ->make(true);
        }
        return view('users.feedbacklist');
    }

    public function deleteFeedback($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        return redirect()->route('feedback.list')->with('success', 'Feedback deleted successfully.');
    }


    public function transactionList(Request $request)
    {
        if ($request->ajax()) {

            $query = DB::table('payments as p')
                ->leftJoin('admin as a', 'p.user_id', '=', 'a.id')
                ->leftJoin('subscription_plans as s', 'p.packageid', '=', 's.id')
                ->select(
                    'p.id',
                    'p.user_id',
                    'p.date',
                    'p.amount',
                    'p.transactionid',
                    'p.status as payment_status',
                    'p.created_at',
                    'a.business_name',
                    'a.mobile',
                    'a.ispaid',
                    's.plan_name'
                );
              

            return DataTables::of($query)
                ->editColumn('date', function ($row) {
                    return $row->date
                        ? \Carbon\Carbon::parse($row->date)->format('d-m-Y')
                        : '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at
                        ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y h:i A')
                        : '-';
                })
                ->editColumn('payment_status', function ($row) {
                    return ucfirst($row->payment_status);
                })

                ->editColumn('ispaid', function ($row) {
                    return ($row->ispaid == 0)  ? 'Free' : 'Paid';
                })
                ->rawColumns(['date','created_at','payment_status','ispaid'])
                ->make(true);
        }
        return view('users.transactionlist');
    }

    private function userPaidStatus($ispaid, $planStatus)
    {
        if ($ispaid == 1 && $planStatus == 2) {
            return 'Paid';
        } elseif ($ispaid == 1 && $planStatus == 1) {
            return 'Trial Active';
        } elseif ($ispaid == 0 && $planStatus == 1) {
            return 'Trial Expired';
        } elseif ($ispaid == 0 && $planStatus == 2) {
            return 'Paid Expired';
        } else {
            return 'Free';
        }
    }
}
