<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Feedback;
use App\Models\Makepost;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\SubscriptionPlan;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       
        if ($request->ajax()) {

            $type    = $request->input('type');
            $version = $request->input('version');

            $query = Admin::from('admin as a')
                ->leftJoin('notification as n', 'a.id', '=', 'n.user_id')
                ->leftJoin('daily_post_count as dp', 'a.id', '=', 'dp.user_id')
                ->where('a.role', 'User')
                ->select(
                    'a.id',
                    'a.business_name',
                    'a.mobile',
                    'a.ispaid',
                    'a.planStatus',
                    'a.expdate',
                    'a.status',
                    'a.otp',
                    'a.created_at',
                    'a.photo',
                    'n.app_version',
                    'dp.tamp_count'
                );

            /* Join payments ONLY for paid users (type = 2) */
            if ($type == 2) {
                $query->leftJoin('payments as p', 'p.user_id', '=', 'a.id');
            }

            /* Version Filter */
            if ($version) {
                $query->where('n.app_version', $version);
            }

            /* ===============================
            USER TYPE FILTERS (CI MATCH)
            ================================ */
            switch ($type) {

                // 1 = New User (NO condition in CI)
                case 1:
                    break;

                // 2 = Total Package Paid User
                case 2:
                    $query->where('a.ispaid', 1)
                        ->where('a.planStatus', 2);
                    break;

                // 3 = Trial Plan Active User
                case 3:
                    $query->where('a.ispaid', 1)
                        ->where('a.planStatus', 1);
                    break;

                // 4 = Without Logo
                case 4:
                    $query->where(function ($q) {
                        $q->whereNull('a.photo')
                        ->orWhere('a.photo', '');
                    });
                    break;

                // 5 = Trial Plan Expired User
                case 5:
                    $query->where('a.ispaid', 0)
                        ->where('a.planStatus', 1);
                    break;

                // 6 = Total Package Expired User
                case 6:
                    $query->where('a.ispaid', 1)
                        ->where('a.planStatus', 2);
                    break;

                // 8 = Total Free User
                case 8:
                    $query->where('a.ispaid', 0)
                        ->whereNull('a.planStatus');
                    break;
                
                case 9:
                $query->where('a.ispaid', 0)
                    ->where('a.planStatus', 2);
                break;
            }

            /* ===============================
            DATE FILTER (EXACT CI LOGIC)
            ================================ */
           if ($type == 2) {
            // Paid users → payment date
                if ($request->filled('start_date')) {
                    $query->whereDate('p.pdate', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('p.pdate', '<=', $request->end_date);
                }
            }
            elseif (in_array($type, [6, 9])) {
                // Expired users → expiry date
                if ($request->filled('start_date')) {
                    $query->whereDate('a.expdate', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('a.expdate', '<=', $request->end_date);
                }
            }
            else {
                // All other users → created date
                if ($request->filled('start_date')) {
                    $query->whereDate('a.created_at', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate(
                        'a.created_at',
                        '<=',
                        \Carbon\Carbon::parse($request->end_date)->addDay()
                    );
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('post', function ($user) {
                    return $user->countUserPostTotal($user->id);
                })
                ->filterColumn('post', function ($query, $keyword) {
                        $query->where('dp.tamp_count', 'LIKE', "%{$keyword}%");
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
                    $title = $this->userPaidStatus($user->ispaid, $user->planStatus);
                    $icon  = $user->ispaid ? 'fa-check-circle icolgreen' : 'fa-times-circle icolred';
                    return '<i class="fa '.$icon.'"></i> '.$title;
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
                    $buttons = '';
                    $user_details   = route('user.details', $user->id);
                    // User details
                    $buttons .= '
                        <a href="#" class="btn btn-sm"
                            data-ajax-popup="true" data-size="lg"
                            data-title="User Detail" data-url="' . $user_details . '"
                            data-bs-toggle="tooltip" data-bs-original-title="User Detail">
                            <i class="fa fa-user me-2"></i>
                        </a>';

                    
                    $changepassword = route('user.changePassword', $user->id);

                    // Change password
                    $buttons .= '
                        <a href="#" class="btn btn-sm"
                            data-ajax-popup="true" data-size="md"
                            data-title="Change Password" data-url="' . $changepassword . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Change Password">
                            <i class="fa fa-key me-2"></i>
                        </a>';

                    // View
                    $buttons .= '
                        <a href="' . route('user.customframe', $user->id) . '" class="btn btn-sm">
                            <i class="fa fa-eye me-2"></i>
                        </a>';

                    // Edit
                    if (auth()->user()->can('user-edit')) {
                        $buttons .= '
                            <a href="' . route('user.edit', $user->id) . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                            </a>';
                    }

                    if (auth()->user()->can('user-delete')) {
                        $buttons .= '
                            <button class="btn btn-sm delete-btn"
                                data-url="' . route('user.destroy', $user->id) . '">
                                <i class="fa fa-trash me-2"></i>
                            </button>';
                    }
                    return $buttons;
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
        if (auth()->user()->can('user-create')) {
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
            $user->role          = 'User'; // User Role

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
        } else {
            return redirect()->route('user.index')->with('error', 'Permission Denied !');
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
        if (auth()->user()->can('user-edit')) {
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
        } else {
            return redirect()->route('user.index')->with('error', 'Permission Denied !');
        }
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy($id)
    {
        if (auth()->user()->can('user-delete')) {
            $user = Admin::findOrFail($id);
            // Delete image
            if ($user->business_logo && Storage::disk('public')->exists($user->business_logo)) {
                Storage::disk('public')->delete($user->business_logo);
            }
            $user->delete();
            return redirect()->route('user.index')->with('success', 'User deleted successfully.');
        } else {
            return redirect()->route('adx.index')->with('error', 'Permission Denied !');
        }
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
        $user->password = md5($request->new_password);
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
        if (auth()->user()->can('user-transaction')) {
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
                
                if ($request->filled('start_date')) {
                    $query->whereDate('p.created_at', '>=', $request->start_date);
                }
                
                if ($request->filled('end_date')) {
                    $query->whereDate('p.created_at', '<=', $request->end_date);
                }
                

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
        } else {
            return redirect()->route('adx.index')->with('error', 'Permission Denied !');
        }
    }

     private function userPaidStatus($ispaid, $planStatus)
    {
        if($ispaid==1 && $planStatus==2){
            $paidStatus = "Paid";
        }elseif($ispaid==1 && $planStatus==1){
            $paidStatus = "Trial Active";
        }elseif($ispaid==0 && $planStatus==1){
            $paidStatus = "Trial Expired";
        }elseif($ispaid==0 && $planStatus==2){
            $paidStatus = "Free Plan Expired";
        }else{
            $paidStatus = "Free";
        }
        
        return $paidStatus;
    }


    public function postList(Request $request)  
    {
        if ($request->ajax()) {
             $query = DB::table('makepost as m')
                ->leftJoin('admin as a', 'm.user_id', '=', 'a.id')
                ->select('m.*', 'a.name','a.mobile')
               ->get();
              return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('tamplate', function ($row) {
                     $editUrl = route('tamplet.edit', $row->tamp_id);
                     return '<a class="image-popup-no-margins" target="_blank" href="' . $editUrl . '">'.$row->tamp_id.'</a>';
                })
                ->editColumn('post', function ($row) {
                    $imagePath = $row->post ? asset('storage/' . ltrim($row->post, '/')) : null;
                    if (!empty($row->post)) {
                        return '
                            <a class="image-popup-no-margins" href="' . $imagePath . '">
                                <img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">
                            </a>
                            ';
                    } else {
                        return 'No Post';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? with(new \Carbon\Carbon($row->created_at))->format('d/m/Y') : '';
                })
                ->editColumn('updated_at', function ($row) {
                    return $row->updated_at ? with(new \Carbon\Carbon($row->updated_at))->format('d/m/Y') : '';
                })
                ->addColumn('actions', function ($row) {
                    $buttons  = '';
                    $deleteUrl = route('user.post.delete', $row->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['tamplate','post','created_at','updated_at','actions'])
                ->make(true);
        }
        return view('users.postlist');
    }

    public function activePremiumUsers(Request $request)
    {
        if ($request->ajax()) {

            $query = Admin::query()
                ->where('role', 'User')      // change to 1 if numeric
                ->where('ispaid', 1)
                ->where('planStatus', 2)
                ->select([
                    'id',
                    'business_name',
                    'mobile',
                    'ispaid',
                    'planStatus',
                    'expdate',
                    'status',
                    'otp',
                    'created_at',
                    'photo',
                ]);

            return DataTables::of($query)
                ->addIndexColumn()

                /* Post Count */
                ->addColumn('post', function ($user) {
                    return DB::table('makepost')
                        ->where('user_id', $user->id)
                        ->count();
                })

                /* App Version */
                ->addColumn('app_version', function ($user) {
                    return DB::table('notification')
                        ->where('user_id', $user->id)
                        ->latest('id')
                        ->value('app_version');
                })

                /* Created */
                ->editColumn('created_at', function ($user) {
                    return $user->created_at
                        ? $user->created_at->format('d-m-Y h:i A')
                        : '';
                })

                /* Photo */
                ->addColumn('photo', function ($user) {
                    if (!$user->photo) {
                        return 'No Logo';
                    }

                    $url = asset('storage/' . ltrim($user->photo, '/'));
                    return "<a href='{$url}' target='_blank'>
                                <img src='{$url}' width='30' height='30' class='rounded'>
                            </a>";
                })

                /* Mobile */
                ->editColumn('mobile', function ($user) {
                    return "<a target='_blank'
                                href='https://web.whatsapp.com/send?phone=91{$user->mobile}'
                                style='color:#0088cc'>
                                {$user->mobile}
                            </a>";
                })

                /* Paid */
                ->editColumn('ispaid', function () {
                    return '<span class="badge bg-success">Paid</span>';
                })

                /* Status Toggle */
                ->addColumn('status', function ($user) {
                    $checked = $user->status == 1 ? 'checked' : '';
                    return "
                        <label class='custom-switch'>
                            <input type='checkbox' class='status-toggle'
                                data-id='{$user->id}' {$checked}>
                            <span class='switch-slider'></span>
                        </label>";
                })

                /* Expiry */
                ->editColumn('expdate', function ($user) {
                    return $user->expdate
                        ? \Carbon\Carbon::parse($user->expdate)->format('d/m/Y')
                        : '';
                })

                /* Actions */
                ->addColumn('actions', function ($user) {
                    $btn = '';

                    $btn .= "<a href='#' class='btn btn-sm'
                                data-ajax-popup='true'
                                data-title='Change Password'
                                data-url='".route('user.changePassword', $user->id)."'>
                                <i class='fa fa-key'></i>
                            </a>";

                    $btn .= "<a href='".route('user.customframe', $user->id)."' class='btn btn-sm'>
                                <i class='fa fa-eye'></i>
                            </a>";

                    if (auth()->user()->can('user-edit')) {
                        $btn .= "<a href='".route('user.edit', $user->id)."' class='btn btn-sm'>
                                    <i class='fa fa-edit'></i>
                                </a>";
                    }

                    if (auth()->user()->can('user-delete')) {
                        $btn .= "<button class='btn btn-sm delete-btn'
                                    data-url='".route('user.destroy', $user->id)."'>
                                    <i class='fa fa-trash'></i>
                                </button>";
                    }

                    return $btn;
                })

                ->rawColumns(['photo','mobile','ispaid','status','actions'])
                ->make(true);
        }

        return view('users.index');
    }
    public function deletePost($id)
    {
        $post = Makepost::findOrFail($id);
        $post->delete();
        return redirect()->route('post.list')->with('success', 'Post deleted successfully.');
    }

    public function userdetails($id)
    {
        
        /* ------------------------------
        | 1. User + total posts
        ------------------------------*/
        $user = Admin::where('id', $id)->firstOrFail();

        $userData = $user->toArray();

        /* Photo */
        $userData['photo'] = $user->photo
            ? asset('storage/' . ltrim($user->photo, '/'))
            : asset('assets/images/Admin.png');

        /* Date formatting */
        $userData['created_at'] = $user->created_at
            ? $user->created_at->format('d/m/Y H:i')
            : '-';

        $userData['updated_at'] = $user->updated_at
            ? $user->updated_at->format('d/m/Y H:i')
            : '-';

        $userData['last_login'] = $user->last_login
            ? Carbon::parse($user->last_login)->format('d/m/Y H:i')
            : '-';

        $userData['expdate'] = $user->expdate
            ? Carbon::parse($user->expdate)->format('d/m/Y')
            : '-';

        $userData['totalPost'] = DB::table('makepost')
            ->where('user_id', $id)
            ->count();

        /* ------------------------------
        | 2. Payment history
        ------------------------------*/
        $payments = Payment::where('user_id', $id)
            ->leftJoin('subscription_plans as s', 'payments.packageid', '=', 's.id')
            ->select(
                'payments.*',
                's.plan_name',
                's.duration',
                's.duration_type',
                's.price as plan_price'
            )
            ->orderByDesc('payments.id')
            ->get()
            ->map(function ($p) {
                return [
                    ...$p->toArray(),
                    'created_at' => $p->created_at
                        ? Carbon::parse($p->created_at)->format('d/m/Y H:i')
                        : '-',
                    'date' => $p->date
                        ? Carbon::parse($p->date)->format('d/m/Y')
                        : '-',
                    'refundDate' => $p->refundDate
                        ? Carbon::parse($p->refundDate)->format('d/m/Y H:i')
                        : null,
                ];
            });

        $userData['payments'] = $payments;

        /* ------------------------------
        | 3. Device / Notification info
        ------------------------------*/
        $userData['deviceInfo'] = DB::table('notification')
            ->where('user_id', $id)
            ->select('id','device_id', 'user_id', 'app_version', 'oprating_system')
            ->get();

        /* ------------------------------
        | 4. Active packages
        ------------------------------*/
        $userData['packageList'] = SubscriptionPlan::where('status', 1)
            ->select('id', 'plan_name', 'price','discount')
            ->get();

        /* ------------------------------
        | 5. Total custom frames
        ------------------------------*/
        $userData['totalCustomFrame'] = DB::table('customframe')
            ->where('user_id', $id)
            ->count();

        /* ------------------------------
        | 6. Payment Links
        ------------------------------*/
        $userData['paymentLinks'] = DB::table('payment_link')
            ->where('user_id', $id)
            ->select('id', 'mobile', 'attempts', 'exp_date', 'created_at')
            ->orderByDesc('id')
            ->get()
            ->map(function ($link) {
                return [
                    ...(array)$link,
                    'exp_date' => $link->exp_date
                        ? Carbon::parse($link->exp_date)->format('d/m/Y H:i')
                        : '-',
                    'created_at' => $link->created_at
                        ? Carbon::parse($link->created_at)->format('d/m/Y H:i')
                        : '-',
                ];
            });

        return view('users.user_detail', compact('userData'));
    }

    public function sendPaymentLink(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request']);
        }

        $userId = $request->input('user_id');
        $amount = $request->input('amount');

        if (empty($userId) || empty($amount)) {
            return response()->json(['status' => 'error', 'message' => 'Some fields are required!']);
        }

        // Get user details
        $user = Admin::select('b_email', 'mobile')->where('id', $userId)->first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found!']);
        }

        $mobile = $user->mobile;
        $email = $user->b_email;
        $description = "Reference No. #" . $userId;

        $emailSend = ($email != "brandfotoss@gmail.com" && !empty($email));
        

        $userData = [
            'token' => config('app.payment_token'), // Add this to your .env
            'amount' => $amount * 100,
            'expire_by' => strtotime("+2 days"),
            'reference_id' => "ref_" . time() . "_" . $userId,
            'description' => $description,
            'name' => "",
            'email' => $email,
            'contact' => "+91" . $mobile,
            'smsOn' => true,
            'emailOn' => $emailSend,
            'user_id' => $userId,
            'type' => "link",
            'callback_url' => "",
            'callback_method' => "",
        ];

        // Check existing payment link
        $existingLink = DB::table('payment_link')->where('mobile', $mobile)->where('exp_date', '>', now())->orderBy('id', 'desc')->first();
        if ($existingLink) {
            $resendData = [
                'token' => config('app.payment_token'),
                'options' => "sms",
                'paymentid' => $existingLink->paymentLinkId,
                'link' => $existingLink->link,
                'mobile' => $existingLink->mobile,
            ];
            $result = paymentlinkResend($resendData);
        } else {
            $result = paymentLinkCreateForUser_post($userData);
        }

        if ($result) {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment link sent successfully...' . $mobile . " - " . $email
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Error something else!'
            ]);
        }
    }
   
    public function user_delete_notification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return redirect()->route('user.index')->with('success', 'Notification deleted successfully.');
    }

    
}