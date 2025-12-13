<?php

namespace App\Http\Controllers;

use App\Models\AdsApi;
use App\Models\ApplicationAdd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $advertisements = AdsApi::with(['ads_app'])->orderBy('id', 'desc');
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
        return view('advertisement.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $applications = ApplicationAdd::get();
        return view('advertisement.create',compact('applications'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'ads_title' => ['required', 'string', 'max:255'],
            'ads_id'    => ['required'],
            'app_id'    => ['required'],
            'ads_type'    => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $advertisement             = new AdsApi();
        $advertisement->ads_title  = $request->ads_title;
        $advertisement->ads_id     = $request->ads_id;
        $advertisement->app_id     = $request->app_id;
        $advertisement->ads_type   = $request->ads_type;
        $advertisement->save();

        return redirect()->route('advertisement.index')->with('success', 'Advertisement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdsApi $advertisement)
    {
        $applications = ApplicationAdd::get();
        return view('advertisement.edit', compact('advertisement','applications'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdsApi $advertisement)
    {
         $validator = Validator::make($request->all(), [
            'ads_title'  => ['required', 'string', 'max:255'],
            'ads_id'     => ['required'],
            'app_id'     => ['required'],
            'ads_type'   => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $advertisement->ads_title  = $request->ads_title;
        $advertisement->ads_id     = $request->ads_id;
        $advertisement->app_id     = $request->app_id;
        $advertisement->ads_type   = $request->ads_type;
        $advertisement->update();

        return redirect()->route('advertisement.index')->with('success', 'Advertisement updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $advertisement = AdsApi::findOrFail($id);
        $advertisement->delete();
        return redirect()->route('advertisement.index')->with('success', 'Advertisement deleted successfully.');
    }
}
