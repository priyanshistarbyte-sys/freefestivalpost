<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\SubFrame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubFrameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = SubFrame::with(['frame'])->orderBy('id', 'desc');
               return DataTables::of($query)
                ->addColumn('frame', function ($category) {
                    return $category->frame ? $category->frame->frame_name : '-';
                })
                ->addColumn('status', function ($subFrame) {
                      $checked = $subFrame->status == 1 ? 'checked' : '';
                        return '
                            <label class = "custom-switch">
                            <input type  = "checkbox" class = "status-toggle" data-id = "'.$subFrame->id.'" '.$checked.'>
                            <span  class = "switch-slider"></span>
                            </label>';
                })
                ->addColumn('image', function ($subFrame) {
                    $imagePath = $subFrame->image ? asset('storage/' . ltrim($subFrame->image, '/')) : null;
                    if (!empty($subFrame->image)) {
                        return '
                            <a   class = "image-popup-no-margins" href = "' . $imagePath . '">
                            <img class = "img-responsive" src          = "' . $imagePath . '" alt = "Icon" class = "dataTable-app-img rounded" width = "20" height = "20">
                            </a>
                            ';
                    } else {
                        return '<img src="' . asset('assets/images/default.jpg') . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">';
                    }
                })
                ->addColumn('actions', function ($subFrame) {
                    $buttons  = '';
                    $editUrl  = route('sub-frame.edit', $subFrame->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm"
                               data-ajax-popup = "true" data-size = "md"  data-title= "Edit Sub Frame" data-url = "' . $editUrl . '"
                               data-bs-toggle  = "tooltip" data-bs-original-title = "Edit">
                            <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl  = route('sub-frame.destroy', $subFrame->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['frame','status','image','actions'])
                ->make(true);
        }
        return view('sub-frame.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $frames = Frame::where('status',1)->get();
        return view('sub-frame.create',compact('frames'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'frame_id'    => ['required'],
            'image'       => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/sub_frames', 'public');
        }

        $subFrame              = new SubFrame();
        $subFrame->frame_id    = $request->frame_id;
        $subFrame->status      = $request->status ?? 0;
        $subFrame->image       = $imagePath;
        $subFrame->save();

        return redirect()->route('sub-frame.index')->with('success', 'Sub Frame created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubFrame $subFrame)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubFrame $subFrame)
    {
       $frames = Frame::where('status',1)->get();
       return view('sub-frame.edit',compact('subFrame','frames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubFrame $subFrame)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'frame_id'    => ['required'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($subFrame->image && Storage::disk('public')->exists($subFrame->image)) {
                Storage::disk('public')->delete($subFrame->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('uploads/images/sub_frames', 'public');
            $subFrame->image = $imagePath;
        }

        // Update fields
        $subFrame->frame_id    = $request->frame_id;
        $subFrame->status      = $request->status ?? 0;
        $subFrame->save();

        return redirect()->route('sub-frame.index')->with('success', 'Sub Frame updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubFrame $subFrame)
    {
        $subFrame->delete();
        return redirect()->route('sub-frame.index')->with('success', 'Sub Frame deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $subFrame = SubFrame::find($request->id);
        if (!$subFrame) {
            return response()->json(['success' => false, 'message' => 'Sub Frame not found.']);
        }
        $subFrame->status = $request->status;
        $subFrame->save();
        return response()->json(['success' => true, 'message' => 'Sub Frame updated successfully.']);
    }
}
