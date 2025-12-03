<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Customframe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CustomframeController extends Controller
{
    public function index(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        if ($request->ajax()) {
            $query = Customframe::with('user')->where('user_id', $id);
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($customframe) {
                    $checked = $customframe->status == 1 ? 'checked' : '';
                    return '
                        <label class="custom-switch">
                            <input type="checkbox" class="status-toggle" data-id="' . $customframe->id . '" ' . $checked . '>
                            <span class="switch-slider"></span>
                        </label>';
                })
                ->addColumn('image', function ($customframe) {
                    $imagePath = $customframe->image ? asset('storage/' . ltrim($customframe->image, '/')) : null;
                    if (!empty($customframe->image)) {
                        return '
                            <a class="image-popup-no-margins" href="' . e($imagePath) . '">
                                <img class="img-responsive" src="' . e($imagePath) . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
                            </a>
                            ';
                    } else {
                        return '<img src="' . asset('assets/images/default.jpg') . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">';
                    }
                })
                ->editColumn('created_at', function ($feedback) {
                    return $feedback->created_at ? with(new \Carbon\Carbon($feedback->created_at))->format('d-m-Y h:m') : '';
                })
                ->editColumn('updated_at', function ($feedback) {
                    return $feedback->updated_at ? with(new \Carbon\Carbon($feedback->updated_at))->format('d-m-Y h:m') : '';
                })
                ->addColumn('actions', function ($customframe) {
                    $buttons = '';
                    $editUrl = route('edit.customframe', [$customframe->user_id, $customframe->id]);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Category" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('delete.customframe', [$customframe->user_id, $customframe->id]);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['status', 'image', 'created_at', 'updated_at', 'actions'])
                ->make(true);
        }
        return view('customframe.index', compact('user'));
    }

    public function create($id)
    {
        $user = Admin::findOrFail($id);
        return view('customframe.create', compact('user'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'frame_name' => 'required|string|max:255',
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        // Upload Image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/images/customframe', 'public');
        }

        $frame = new Customframe();
        $frame->user_id = $id;
        $frame->frame_name = $request->frame_name;
        $frame->image = $imagePath;
        $frame->status = $request->status ?? '0';
        $frame->free_paid = 1;
        $frame->save();

        return redirect()->back()->with('success', 'Frame created successfully');
    }

    public function edit($id, $cid)
    {
        $user = Admin::findOrFail($id);
        $frame = Customframe::findOrFail($cid);

        return view('customframe.edit', compact('user', 'frame'));
    }

    public function update(Request $request, $id, $cid)
    {
        $frame = Customframe::findOrFail($cid);

        $request->validate([
            'frame_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $frame->frame_name = $request->frame_name;

        // Replace Image (if uploaded)
        if ($request->hasFile('image')) {

            if ($frame->image && Storage::disk('public')->exists($frame->image)) {
                Storage::disk('public')->delete($frame->image);
            }

            $frame->image = $request->file('image')->store('uploads/images/customframe', 'public');
        }
        $frame->status = $request->status ?? '0';
        $frame->save();

        return redirect()->back()->with('success', 'Frame updated successfully!');
    }

    public function destroy($id, $cid)
    {
        $frame = Customframe::findOrFail($cid);

        // Delete image
        if ($frame->image && Storage::disk('public')->exists($frame->image)) {
            Storage::disk('public')->delete($frame->image);
        }

        $frame->delete();

        return redirect()->back()->with('success', 'Frame deleted successfully!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $frame = Customframe::find($request->id);
        if (!$frame) {
            return response()->json(['success' => false, 'message' => 'Frame not found']);
        }

        $frame->status = $request->status;
        $frame->save();

        return response()->json(['success' => true]);
    }
}
