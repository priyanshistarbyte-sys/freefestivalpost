<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FrameController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = Frame::orderBy('id', 'desc');
               return DataTables::of($query)
                ->addColumn('status', function ($frame) {
                      $checked = $frame->status == 1 ? 'checked' : '';
                        return '
                            <label class = "custom-switch">
                            <input type  = "checkbox" class = "status-toggle" data-id = "'.$frame->id.'" '.$checked.'>
                            <span  class = "switch-slider"></span>
                            </label>';
                })
                ->addColumn('free_paid', function ($frame) {
                    $checked = $frame->free_paid == 1 ? 'checked' : '';
                    return '
                        <label class = "custom-switch">
                        <input type  = "checkbox" class = "free-paid-toggle" data-id = "'.$frame->id.'" '.$checked.'>
                        <span  class = "switch-slider"></span>
                        </label>';
                })
                ->addColumn('image', function ($frame) {
                    $imagePath = $frame->image ? asset('storage/' . ltrim($frame->image, '/')) : null;
                    if (!empty($frame->image)) {
                        return '
                            <a   class = "image-popup-no-margins" href = "' . $imagePath . '">
                            <img class = "img-responsive" src          = "' . $imagePath . '" alt = "Icon" class = "dataTable-app-img rounded" width = "20" height = "20">
                            </a>
                            ';
                    } else {
                        return '<img src="' . asset('assets/images/default.jpg') . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">';
                    }
                })
                ->addColumn('actions', function ($frame) {
                    $buttons  = '';
                    $editUrl  = route('frame.edit', $frame->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm"
                               data-ajax-popup = "true" data-size = "md"  data-title= "Edit Frame" data-url = "' . $editUrl . '"
                               data-bs-toggle  = "tooltip" data-bs-original-title = "Edit">
                            <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl  = route('frame.destroy', $frame->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['status','free_paid','image','actions'])
                ->make(true);
        }
        return view('frames.index');
    }

      /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('frames.create');
    }

      /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
         $validator = Validator::make($request->all(), [
            'frame_name'  => ['required', 'string', 'max:255'],
            'image'       => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'data'        => ['nullable'],
            'logosection' => ['nullable'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/frames', 'public');
        }

    
        $frame              = new Frame();
        $frame->frame_name  = $request->frame_name;
        $frame->data        = $request->data;
        $frame->logosection = $request->logosection;
        $frame->status      = $request->status ?? 0;
        $frame->free_paid   = $request->free_paid ?? 0;  
        $frame->image       = $imagePath;

        $frame->save(); 
        return redirect()->route('frame.index')->with('success', 'Frame created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Frame $frame)
    {
        return redirect()->back();
    }

      /**
     * Show the form for editing the specified resource.
     */
    public function edit(Frame $frame)
    {
        return view('frames.edit',compact('frame'));
    }

      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Frame $frame)
    {
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'frame_name'  => ['required', 'string', 'max:255'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'data'        => ['nullable'],
            'logosection' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($frame->image && Storage::disk('public')->exists($frame->image)) {
                Storage::disk('public')->delete($frame->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('uploads/images/frames', 'public');
            $frame->image = $imagePath;
        }

        // Update fields
        $frame->frame_name  = $request->frame_name;
        $frame->data        = $request->data;
        $frame->logosection = $request->logosection;
        $frame->status      = $request->status ?? 0;
        $frame->free_paid   = $request->free_paid ?? 0;  
        $frame->save();

        return redirect()->route('frame.index')->with('success', 'Frame updated successfully.');
    }

    public function destroy($id)
    {
        $frame = Frame::findOrFail($id);
        // Delete image
        if ($frame->image && Storage::disk('public')->exists($frame->image)) {
            Storage::disk('public')->delete($frame->image);
        }
        $frame->delete();
        return redirect()->route('frame.index')->with('success', 'Frame deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $frame = Frame::find($request->id);
        if (!$frame) {
            return response()->json(['success' => false, 'message' => 'Frame not found']);
        }

        $frame->status = $request->status;
        $frame->save();

        return response()->json(['success' => true, 'message' => 'Frame updated successfully']);
    }

    public function updateFreePaid(Request $request)
    {
        $frame = Frame::find($request->id);
        if (!$frame) {
            return response()->json(['success' => false, 'message' => 'Frame not found']);
        }

        $frame->free_paid = $request->free_paid;
        $frame->save();

        return response()->json(['success' => true, 'message' => 'Frame updated successfully']);
    }

    
}
