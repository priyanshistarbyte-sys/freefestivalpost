<?php

namespace App\Http\Controllers;

use App\Models\AppSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AppSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          if ($request->ajax()) {
            $query = AppSlider::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('festivalDate', function ($appSlider) {
                    return $appSlider->festivalDate ? with(new \Carbon\Carbon($appSlider->festivalDate))->format('d-m-Y') : '';
                })
                ->addColumn('image', function ($appSlider) {
                    $imagePath = $appSlider->image ? asset('storage/' . ltrim($appSlider->image, '/')) : null;
                    if (!empty($appSlider->image)) {
                        return '
                            <a class="image-popup-no-margins" href="' . $imagePath . '">
                                <img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
                            </a>
                            ';
                    } else {
                        return '<img src="' . asset('assets/images/default.jpg') . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">';
                    }
                })
                ->addColumn('status', function ($appSlider) {
                       $checked = $appSlider->status == 1 ? 'checked' : '';
                        return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$appSlider->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->editColumn('url', function ($appSlider) {
                    return '<a href="'.$appSlider->url.'" target="_blank">'.$appSlider->url.'</a>';
                })
                ->addColumn('actions', function ($appSlider) {
                    $buttons = '';
                    $editUrl = route('app-slider.edit', $appSlider->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                             </a>
                            ';
                    $deleteUrl = route('app-slider.destroy', $appSlider->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> 
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['festivalDate','image','status','url','actions'])
                ->make(true);
        }
        return view('app-slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('app-slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        if ($request->hasFile('image')) {
            $path_image = $request->file('image')->store('uploads/images/app_slider_image', 'public');
        }

        $appSlider                 = new AppSlider();
        $appSlider->title          = $request->title;
        $appSlider->image          = $path_image ?? '';
        $appSlider->sort           = $request->sort ?? 0;
        $appSlider->mid            = $request->mid ?? '';
        $appSlider->sub            = $request->sub ?? '';
        $appSlider->festivalDate   = $request->festivalDate ?? null;
        $appSlider->start_date     = $request->start_date ?? null;
        $appSlider->end_date       = $request->end_date ?? null;
        $appSlider->url            = $request->url ?? '';
        $appSlider->status         = $request->status ? 1 : 0;  // FIXED
        $appSlider->save();

        return redirect()->route('app-slider.index')->with('success', 'App Slider created successfully.');
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
    public function edit(AppSlider $appSlider)
    {
       return view('app-slider.edit',compact('appSlider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppSlider $appSlider)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
           
        ]);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
         if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($appSlider->image && Storage::disk('public')->exists($appSlider->image)) {
                Storage::disk('public')->delete($appSlider->image);
            }
            // Upload new image
            $path_image = $request->file('image')->store('uploads/images/app_slider_image', 'public');
        }
   
        $appSlider->title          = $request->title;
        $appSlider->image          = $path_image ?? '';
        $appSlider->sort           = $request->sort ?? 0;
        $appSlider->mid            = $request->mid ?? '';
        $appSlider->sub            = $request->sub ?? '';
        $appSlider->festivalDate   = $request->festivalDate ?? null;
        $appSlider->start_date     = $request->start_date ?? null;
        $appSlider->end_date       = $request->end_date ?? null;
        $appSlider->url            = $request->url ?? '';
        $appSlider->status         = $request->status ? 1 : 0;  
        $appSlider->save();
        return redirect()->route('app-slider.index')->with('success', 'App Slider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appSlider = AppSlider::findOrFail($id);
        // Delete image
        if ($appSlider->image && Storage::disk('public')->exists($appSlider->image)) {
            Storage::disk('public')->delete($appSlider->image);
        }
        $appSlider->delete();
        return redirect()->route('app-slider.index')->with('success', 'App Slider deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $appSlider = AppSlider::find($request->id);
        if (!$appSlider) {
            return response()->json(['success' => false, 'message' => 'App Slider not found.']);
        }
        $appSlider->status = $request->status;
        $appSlider->save();
        return response()->json(['success' => true, 'message' => 'App Slider updated successfully.']);
    }
}
