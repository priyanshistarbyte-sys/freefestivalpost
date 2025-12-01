<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionDescription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;


class SubscriptionPlanController extends Controller
{
          /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SubscriptionPlan::with('descriptionsItem')->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('title', function ($plan) {
                    if (!$plan->descriptionsItem || $plan->descriptionsItem->count() == 0) {
                        return '<div>No Titles</div>';
                    }
                    return $plan->descriptionsItem
                        ->map(function ($item) {
                            $icon = $item->sign == 1 
                                ? '<i class="fa fa-check fa-2x text-success"></i>' 
                                : '<i class="fa fa-times fa-2x text-primary"></i>';

                            return '
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                    '.$icon.'
                                    <span>'.e($item->title).'</span>
                                </div>
                            ';
                        })
                        ->implode('');
                })
                ->filterColumn('title', function ($query, $keyword) {
                    $query->whereHas('descriptionsItem', function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->editcolumn('is_free', function ($plan) {
                    return $plan->is_free == '0' ? 'Free' : 'Paid';
                })
                ->addColumn('discount', function ($plan) {
                    return $plan->discount . '%';
                })
               ->addColumn('price_section', function ($plan) {
                    $price = number_format($plan->price, 2);
                    // if discount is 0 or discount_price = price → show only normal price
                    if ($plan->discount == 0 || $plan->discount_price == $plan->price) {
                        return '
                            <span style="color: black; font-weight: 600;">
                                ₹'.$price.'
                            </span>
                        ';
                    }
                    // discount applied → show strike + red price
                    $discountPrice = number_format($plan->discount_price, 2);

                    return '
                        <div>
                            <span style="text-decoration: line-through; color: black; font-weight: 600;">
                                ₹'.$price.'
                            </span>
                            <br>
                            <span style="color: red; font-weight: bold; font-size: 15px;">
                                ₹'.$discountPrice.'
                            </span>
                        </div>
                    ';
                })
                ->addColumn('status', function ($plan) {
                        $checked = $plan->status == 1 ? 'checked' : '';
                        return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$plan->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                    })
                ->addColumn('actions', function ($plan) {
                    $buttons = '';
                    $editUrl = route('plan.edit', $plan->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i> 
                             </a>
                            ';
                    $deleteUrl = route('plan.destroy', $plan->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> 
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['title','is_free','price_section','status','actions'])
                ->make(true);
        }
        return view('plan.index');
       
    }

          /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('plan.create');
    }

          /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_name'      => 'required',
            'duration_type'  => 'required',
            'duration'       => 'required',
            'price'          => 'required',
            'discount_price' => 'required',
            'status'         => 'required',
            'sequence'       => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $plan                 = new SubscriptionPlan();
        $plan->plan_name      = $request->plan_name;
        $plan->special_title  = $request->special_title ?? '';
        $plan->duration_type  = $request->duration_type;
        $plan->duration       = $request->duration;
        $plan->price          = $request->price;
        $plan->discount_price = $request->discount_price;
        $plan->discount       = $request->discount ?? '0';
        $plan->status         = $request->status;
        $plan->sequence       = $request->sequence ?? '0';
        $plan->is_free        = $request->is_free;
        $plan->save();

            // Description Items
        $items = $request->items;
        foreach ($items as $item) {
            $description_item          = new SubscriptionDescription();
            $description_item->plan_id = $plan->id;
            $description_item->title   = $item['title'];
            $description_item->sign    = $item['sign'];
            $description_item->save();
        }

        return redirect()->route('plan.index')->with('success', 'Subscription Plan created successfully.');
    }

          /**
     * Display the specified resource.
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
              //
    }

          /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $subscriptionPlan = SubscriptionPlan::with(['descriptionsItem'])->findOrFail($id);
        return view('plan.edit', compact('subscriptionPlan'));
    }

          /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
         $validator = Validator::make($request->all(), [
            'plan_name'      => 'required',
            'duration_type'  => 'required',
            'duration'       => 'required',
            'price'          => 'required',
            'discount_price' => 'required',
            'status'         => 'required',
            'sequence'       => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);

        $subscriptionPlan->plan_name      = $request->plan_name;
        $subscriptionPlan->special_title  = $request->special_title ?? '';
        $subscriptionPlan->duration_type  = $request->duration_type;
        $subscriptionPlan->duration       = $request->duration;
        $subscriptionPlan->price          = $request->price;
        $subscriptionPlan->discount_price = $request->discount_price;   
        $subscriptionPlan->discount       = $request->discount ?? '0';
        $subscriptionPlan->status         = $request->status;
        $subscriptionPlan->sequence       = $request->sequence ?? '0';
        $subscriptionPlan->is_free        = $request->is_free;
        $subscriptionPlan->save();

        // Description Items
        SubscriptionDescription::where('plan_id', $subscriptionPlan->id)->delete();
        $items = $request->items;
        foreach ($items as $item) {
            $description_item          = new SubscriptionDescription();
            $description_item->plan_id = $subscriptionPlan->id;
            $description_item->title   = $item['title'];
            $description_item->sign    = $item['sign'];
            $description_item->save();
        }
        return redirect()->route('plan.index')->with('success', 'Subscription Plan updated successfully.');
    }

          /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $plan = SubscriptionPlan::findOrFail($id);
        SubscriptionDescription::where('plan_id', $plan->id)->delete();
        $plan->delete();
        return redirect()->route('plan.index')->with('success', 'Subscription Plan deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $plan = SubscriptionPlan::find($request->id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan not found']);
        }

        $plan->status = $request->status;
        $plan->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function deleteItem($id)
    {
        $item = SubscriptionDescription::findOrFail($id);
        $item->delete();
        
        return response()->json(['success' => true]);
    }
}
