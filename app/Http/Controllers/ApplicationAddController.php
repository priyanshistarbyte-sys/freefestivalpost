<?php

namespace App\Http\Controllers;

use App\Models\AdsApi;
use App\Models\ApplicationAdd;
use App\Models\Dailog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class ApplicationAddController extends Controller
{
      /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
         if ($request->ajax()) {
              $query = DB::table('application_add as a')
                ->leftJoin(DB::raw('(SELECT app_id, COUNT(id) as totalUnite FROM ads_api GROUP BY app_id) as ap'), 'a.id', '=', 'ap.app_id')
                ->select('a.*', DB::raw('COALESCE(ap.totalUnite, 0) as totalUnite'))
                ->get();
                
              return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('mode', function ($applicationAdd) {
                    if ($applicationAdd->mode == 1) {
                       return '
                            <a href="javascript:void(0);">
                                <button type="button" 
                                    class="btn btn-sm btn-success"
                                    data-toggle="tooltip" title="Live">
                                    <i class="fa fa-circle" aria-hidden="true"></i>
                                </button>
                            </a>
                        ';  
                    } else {
                        return 'Test';
                    }
                })
                ->addColumn('status', function ($applicationAdd) {
                    // status = 0 → Off
                    if ($applicationAdd->status == 0) {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-light" 
                                    data-toggle="tooltip" title="Off">
                                    <i class="fa fa-power-off"></i>
                                </button>
                            </a>
                        ';
                    }

                    // status = 1 → Google
                    else if ($applicationAdd->status == 1) {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-danger" 
                                    data-toggle="tooltip" title="Google">
                                    <i class="fa fa-google"></i>
                                </button>
                            </a>
                        ';
                    }

                    // status = anything else → Facebook
                    else {
                        return '
                            <a href="javascript:void(0);">
                                <button type="button" class="btn btn-sm btn-primary" 
                                    data-toggle="tooltip" title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </button>
                            </a>
                        ';
                    }

                })
                ->addColumn('actions', function ($applicationAdd) {
                    $buttons  = '';
                    $showUrl = route('application.show', Crypt::encrypt($applicationAdd->id)) ;
                    $buttons .= '
                             <a href="' . $showUrl . '" class="btn btn-sm">
                                <i class="fa fa-eye me-2"></i>
                             </a>
                            ';
                    $editUrl = route('application.edit', $applicationAdd->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                             </a>
                            ';
                    return $buttons;
            })
            ->rawColumns(['mode','status','actions'])
            ->make(true);
        }
       return view('application.index');
    }

      /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('application.create');
    }

      /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name'         => ['required', 'string', 'max:255'],
            'app_package_name' => ['required', 'string', 'max:255'],
            'image.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/application_dailog_image', 'public');
        }

        $applicationAdd                   = new ApplicationAdd();
        $applicationAdd->app_name         = $request->app_name;
        $applicationAdd->app_package_name = $request->app_package_name;
        $applicationAdd->adclick          = $request->adclick ?? null;
        $applicationAdd->mode             = $request->mode ?? null;
        $applicationAdd->status           = $request->status ?? '0';
        $applicationAdd->save();

        $dailog                     = new Dailog();
        $dailog->app_id             = $applicationAdd->id;
        $dailog->title            = $request->title ?? null;
        $dailog->button1          = $request->button1 ?? null;
        $dailog->button2          = $request->button2 ?? null;
        $dailog->link             = $request->link ?? null;
        $dailog->appversion       = $request->appversion ?? null;
        $dailog->description      = $request->description ?? null;
        $dailog->isDisplay        = $request->isDisplay ?? null;
        $dailog->forcefully       = $request->forcefully ?? null;
        $dailog->o_type             = $request->o_type ?? null;
        $dailog->o_link             = $request->o_link ?? null;
        $dailog->other_isDisplay  = $request->other_isDisplay ?? null;
        $dailog->other_forcefully = $request->other_forcefully ?? null;
        $dailog->image              = $imagePath;
        $dailog->save();

        return redirect()->route('application.index')->with('success', 'Application created successfully.');
    }

      /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            $appId = Crypt::decrypt($id);
        } catch (\Exception $e) {
            abort(404, 'Invalid application ID');
        }
        $applicationAdd = ApplicationAdd::find($appId);
        if (!$applicationAdd) {
            return redirect()->route('application.index')->with('error', 'Application not found.');
        }

        if ($request->ajax()) {
            $advertisements = AdsApi::with(['ads_app'])->where('app_id', $appId)->orderBy('id', 'desc');
            return DataTables::of($advertisements)
                ->addIndexColumn()
                ->editColumn('ads_type', function ($advertisement) {
                        return $advertisement->ads_type == 1 ? 'Admob' : 'Facebook';
                    })
                    // FILTER FOR ads_type
                    ->filterColumn('ads_type', function($query, $keyword) {
                        $query->where(function($q) use ($keyword) {
                            if (stripos('Admob', $keyword) !== false) {
                                $q->where('ads_type', 1);
                            } elseif (stripos('Facebook', $keyword) !== false) {
                                $q->where('ads_type', 2);
                            } else {
                                // fallback: numeric filter
                                $q->where('ads_type', 'like', "%{$keyword}%");
                            }
                        });
                    })
                ->addColumn('ads_app', function ($advertisement) {
                        return $advertisement->ads_app ? $advertisement->ads_app->app_name : '-';
                    })
                    // FILTER FOR ads_app
                    ->filterColumn('ads_app', function($query, $keyword) {
                        $query->whereHas('ads_app', function($q) use ($keyword) {
                            $q->where('app_name', 'like', "%{$keyword}%");
                        });
                    })
                ->addColumn('actions', function ($advertisement) {
                    $buttons = '';
                    $editUrl = route('advertisement.edit', $advertisement->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Advertisement" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('advertisement.destroy', $advertisement->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['ad_type','ads_app','actions'])
                ->make(true);
        }

        $totaladvertisements = AdsApi::where('app_id', $appId)->count();
        $dailogs = Dailog::where('app_id', $appId)->first(); 
        $analytics = DB::table('appcounter')->where('package_name', $applicationAdd->app_package_name)->orderBy('date', 'desc')->limit(7)->get();
        $today = date('Y-m-d');
        $liveanalytics = DB::table('dailyappcounter')
            ->select(
                'date',
                'package_name',
                DB::raw('SUM(impression) as totalImpression'),
                DB::raw('SUM(new) as totalNew'),
                DB::raw('COUNT(id) as totalActive')
            )
            ->where('date', $today)
            ->where('package_name', $applicationAdd->app_package_name)
            ->groupBy('package_name', 'date')
            ->get();
        return view('application.view', compact('applicationAdd','totaladvertisements','dailogs','analytics','liveanalytics'));
    }

      /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationAdd $application)
    {
        $dailog = Dailog::where('app_id', $application->id)->first();
        return view('application.edit', compact('application', 'dailog'));
    }

      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'app_name'         => ['required', 'string', 'max:255'],
            'app_package_name' => ['required', 'string', 'max:255'],
            'image.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $applicationAdd = ApplicationAdd::find($id);
        if (!$applicationAdd) {
            return redirect()->route('application.index')->with('error', 'Application not found.');
        }
        $applicationAdd->app_name         = $request->app_name;
        $applicationAdd->app_package_name = $request->app_package_name;
        $applicationAdd->adclick          = $request->adclick ?? null;
        $applicationAdd->mode             = $request->mode ?? null;
        $applicationAdd->status           = $request->status ?? '0';
        $applicationAdd->update();

        
        $dailog = Dailog::where('app_id', $applicationAdd->id)->first();
        if (!$dailog) {
            $dailog = new Dailog();
        }

        $imagePath = $dailog->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/application_dailog_image', 'public');
        }
       
        $dailog->app_id             = $applicationAdd->id;
        $dailog->title            = $request->title ?? null;
        $dailog->button1          = $request->button1 ?? null;
        $dailog->button2          = $request->button2 ?? null;
        $dailog->link             = $request->link ?? null;
        $dailog->appversion       = $request->appversion ?? null;
        $dailog->description      = $request->description ?? null;
        $dailog->isDisplay        = $request->isDisplay ?? null;
        $dailog->forcefully       = $request->forcefully ?? null;
        $dailog->o_type             = $request->o_type ?? null;
        $dailog->o_link             = $request->o_link ?? null;
        $dailog->other_isDisplay  = $request->other_isDisplay ?? null;
        $dailog->other_forcefully = $request->other_forcefully ?? null;
        $dailog->image              = $imagePath;
        $dailog->save();

        return redirect()->route('application.index')->with('success', 'Application updated successfully.');
    }

      /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationAdd $applicationAdd)
    {
          //
    }
}
