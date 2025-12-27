<?php

namespace App\Http\Controllers;

use App\Models\CouponCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
           $query = CouponCode::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addColumn('status', function ($couponCode) {
                    return $couponCode->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('note', function ($couponCode) {
                    if($couponCode->note && $couponCode->note != 'null') {
                        return '<div style="max-width: 300px; white-space: normal; word-wrap: break-word;">' . 
                           (strlen($couponCode->note) > 100 ? substr($couponCode->note, 0, 100) . '...' : $couponCode->note) . 
                           '</div>';
                    } 
                    return '-';
                })
                 ->addColumn('actions', function ($couponCode) {
                    $buttons  = '';
                    $editUrl = route('coupon-code.edit', $couponCode->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                             </a>
                            ';
                    $deleteUrl  = route('coupon-code.destroy', $couponCode->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['status', 'note', 'actions'])
                ->make(true);
        }
        return view('coupon-code.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('coupon-code.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => ['required'],
            'title' => ['required'],
            'code'  => ['required'],
            'start_date'  => ['required'],
            'end_date'  => ['required'],
            'total_qty'  => ['required'],
            'total_days'  => ['required'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $couponCode               = new CouponCode();
        $couponCode->name         = $request->name;
        $couponCode->title        = $request->title;
        $couponCode->code         = $request->code;
        $couponCode->start_date   = $request->start_date;
        $couponCode->end_date     = $request->end_date;
        $couponCode->total_qty    = $request->total_qty;
        $couponCode->total_days   = $request->total_days;
        $couponCode->note         = $request->note ?? null;
        $couponCode->status       = $request->status ? 1:0;
        $couponCode->total_count_user_apply = 0;
        $couponCode->save();

        return redirect()->route('coupon-code.index')->with('success', 'Coupon Code created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CouponCode $couponCode)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CouponCode $couponCode)
    {
        return view('coupon-code.edit',compact('couponCode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CouponCode $couponCode)
    {
        $validator = Validator::make($request->all(), [
            'name'  => ['required'],
            'title' => ['required'],
            'code'  => ['required'],
            'start_date'  => ['required'],
            'end_date'  => ['required'],
            'total_qty'  => ['required'],
            'total_days'  => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $couponCode->name       = $request->name;
        $couponCode->title      = $request->title;
        $couponCode->code       = $request->code;
        $couponCode->start_date = $request->start_date;
        $couponCode->end_date   = $request->end_date;
        $couponCode->total_qty  = $request->total_qty;
        $couponCode->total_days = $request->total_days;
        $couponCode->note       = $request->note ?? null;
        $couponCode->status     = $request->status ? 1:0;
        $couponCode->save();

        return redirect()->route('coupon-code.index')->with('success', 'Coupon Code updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $couponCode = CouponCode::findOrFail($id);
        $couponCode->delete();
        return redirect()->route('coupon-code.index')->with('success', 'Coupon Code deleted successfully.');
    }

    public function applyCouponCode($coupon,$userId)
    {
        if (empty($coupon)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon code is required.'
            ]);
        }

        $today = Carbon::today()->toDateString();

        $couponData = CouponCode::where('code', $coupon)
            ->where('status', 1)
            ->whereColumn('total_qty', '>', 'total_count_user_apply')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->first();

        if (!$couponData) {
            return response()->json([
                'status' => 'error',
                'message' => 'This coupon has expired or is invalid.'
            ]);
        }

        $user = Admin::find($userId);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found.'
            ]);
        }

        $alreadyUsed = Payment::where('user_id', $userId)
            ->where('packageid', 1) 
            ->exists();

        if ($alreadyUsed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Coupon already used by this user.'
            ]);
        }

        /** 🧮 Calculate expiry date */
        $expireDate = Carbon::today()->addDays($couponData->total_days)->toDateString();

        DB::transaction(function () use ($couponData, $user, $userId, $expireDate) {
            Payment::create([
                'user_id'        => $userId,
                'amount'         => 0.00,
                'date'           => Carbon::today(),
                'transactionid'  => null,
                'status'         => $couponData->name, 
                'packageid'      => 1,
                'price'          => 0.00,
                'month'          => 0,
            ]);

            /** 👤 Update admin table */
            $user->update([
                'ispaid'     => 1,
                'expdate'    => $expireDate,
                'planStatus' => 1, // trial
                'status'     => 1,
            ]);

            
            $couponData->increment('total_count_user_apply');
        });

        return response()->json([
            'status' => 'success',
            'message' => 'This coupon has been successfully activated.',
            'expire_date' => $expireDate
        ]);
    }
}
