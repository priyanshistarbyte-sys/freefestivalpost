<?php

namespace App\Http\Controllers;

use App\Models\PhotoStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PhotoStatusController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PhotoStatus::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function ($photoStatus) {
                    $imagePath = $photoStatus->image
                        ? asset('storage/' . ltrim($photoStatus->image, '/'))
                        :  asset('assets/images/defaultApp.png');
                    return '
                    <a class="image-popup-no-margins" href="' . $imagePath . '">
						<img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
					</a>
                    ';
                })
              ->addColumn('lablebg', function ($photoStatus) {
                    $color = $photoStatus->lablebg;
                    return '<span style="
                        display:inline-block;
                        width:20px;
                        height:20px;
                        background:' . $color . ';
                        border-radius:4px;
                        border:1px solid #ccc;
                    "></span> ';
                })
                ->addColumn('actions', function ($photoStatus) {
                    $buttons  = '';
                    $editUrl  = route('photo-status.edit', $photoStatus->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Photo Status" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    return $buttons;
                })
                ->rawColumns(['image','lablebg' ,'actions'])
                ->make(true);
        }
        return view('photo-status.index');
    }

      /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('photo-status.create');
    }

      /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $path_image           = $request->file('image')->store('uploads/images/photo_status', 'public');
        $photoStatus          = new PhotoStatus();
        $photoStatus->title   = $request->title;
        $photoStatus->image   = $path_image ?? '';
        $photoStatus->lable   = $request->lable ?? null ;
        $photoStatus->lablebg = $request->lablebg ?? null ;
        $photoStatus->save();

        return redirect()->route('photo-status.index')->with('success', 'Photo Status created successfully.');
    }

      /**
     * Display the specified resource.
     */
    public function show(PhotoStatus $photoStatus)
    {
         return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhotoStatus $photoStatus)
    {
        return view('photo-status.edit', compact('photoStatus'));
    }

      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhotoStatus $photoStatus)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $data = [
            'title' => $request->title,
            'lable'      =>  $request->lable ?? null,
            'lablebg'    =>  $request->lablebg ?? null
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($photoStatus->image && Storage::disk('public')->exists($photoStatus->image)) {
                Storage::disk('public')->delete($photoStatus->image);
            }
            // Upload new image
            $data['image'] = $request->file('image')->store('uploads/images/photo_status', 'public');
        }
        $photoStatus->update($data);
        return redirect()->route('photo-status.index')->with('success', 'Photo Status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
    */
    public function destroy(PhotoStatus $photoStatus)
    {
        $photoStatus->delete();
        return redirect()->route('photo-status.index')->with('success', 'Photo Status deleted successfully.');
    }
}
