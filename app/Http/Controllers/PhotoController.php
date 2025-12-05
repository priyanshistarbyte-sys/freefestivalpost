<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\PhotoStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Photo::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('photo', function ($photo) {
                    $imagePath = $photo->photo
                        ? asset('storage/' . ltrim($photo->photo, '/'))
                        :  asset('assets/images/defaultApp.png');
                    return '
                    <a class="image-popup-no-margins" href="' . $imagePath . '">
						<img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">
					</a>
                    ';
                })
                ->addColumn('status', function ($photo) {
                    return $photo->status ? $photo->status->title : '-';
                })
                ->filterColumn('status', function ($query, $keyword) {
                    $query->whereHas('status', function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? with(new \Carbon\Carbon($user->created_at))->format('d-m-Y') : '';
                })
                ->addColumn('actions', function ($photo) {
                    $buttons  = '';
                    $editUrl = route('photo.edit', $photo->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Photo" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('photo.destroy', $photo->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['photo', 'status', 'created_at', 'actions'])
                ->make(true);
        }
        return view('photo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $photo_status = PhotoStatus::get();
        return view('photo.create',compact('photo_status'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_status_id' => ['required'],
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $path_photo               = $request->file('photo')->store('uploads/images/photo', 'public');
        $photo                    = new Photo();
        $photo->photo             = $path_photo ?? '';
        $photo->photo_status_id   = $request->photo_status_id ?? null;
        $photo->save();

        return redirect()->route('photo.index')->with('success', 'Photo Status created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
       return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        $photo_status = PhotoStatus::get();
        return view('photo.edit', compact('photo','photo_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        $validator = Validator::make($request->all(), [
            'photo_status_id' => ['required'],
            'photo'           => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $data = [
            'photo_status_id' => $request->photo_status_id,
        ];

        if ($request->hasFile('photo')) {
            // Delete old image if exists
            if ($photo->photo && Storage::disk('public')->exists($photo->photo)) {
                Storage::disk('public')->delete($photo->photo);
            }
            // Upload new image
            $data['image'] = $request->file('image')->store('uploads/images/photo', 'public');
        }
        $photo->update($data);
        return redirect()->route('photo.index')->with('success', 'Photo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        // Delete image
        if ($photo->image && Storage::disk('public')->exists($photo->image)) {
            Storage::disk('public')->delete($photo->image);
        }
        $photo->delete();
        return redirect()->route('photo.index')->with('success', 'Photo deleted successfully.');
    }
}
