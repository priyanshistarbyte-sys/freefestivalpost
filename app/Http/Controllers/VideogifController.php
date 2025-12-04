<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Videogif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VideogifController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Videogif::with(['category'])->orderBy('id', 'desc');
               return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function ($Videogif) {
                    return $Videogif->category ? $Videogif->category->mtitle : '-';
                })
                ->filterColumn('category', function($query, $keyword) {
                    $query->whereHas('category', function($q) use ($keyword) {
                        $q->where('mtitle', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('status', function ($Videogif) {
                      $checked = $Videogif->status == 1 ? 'checked' : '';
                        return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$Videogif->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->addColumn('type', function ($Videogif) {
                    return $Videogif->type == 0 ? 'GIF' : 'Video';
                })
              ->addColumn('free_paid', function ($Videogif) {
                    if ($Videogif->free_paid == 1) {
                        $icon = asset('assets/images/paid.svg');
                        return '<img src="'.$icon.'" alt="Paid" width="20">';
                    } else {
                        return '<span class="badge bg-success">Free</span>';
                    }
                })
               
                 ->addColumn('video', function ($Videogif) {
                    if (!$Videogif->path) {
                        return '<span class="text-danger">No Video</span>';
                    }
                    $videoPath = 'http://localhost/freefestivalpost/storage/app/public/' . $Videogif->path;
                    return '
                        <video class="mt-2" width="200" height="100" controls>
                            <source src="'.$videoPath.'" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    ';
                })
                ->addColumn('thumb', function ($Videogif) {
                    $imagePath = $Videogif->thumb
                        ? asset('storage/' . ltrim($Videogif->thumb, '/'))
                        : asset('assets/images/defaultApp.png');
                    return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? with(new \Carbon\Carbon($user->created_at))->format('d-m-Y') : '';
                })
                ->addColumn('actions', function ($Videogif) {
                    $buttons = '';
                    $editUrl = route('videogif.edit', $Videogif->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="lg"
                            data-title="Edit Category" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('videogif.destroy', $Videogif->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['category','status','type','free_paid','video','thumb','created_at' ,'actions'])
                ->make(true);
        }
        
        return view('videogif.index');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $subcategories = SubCategory::where('status', '1')->get();
        return view('videogif.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'            => ['required'],
            'sub_category_id' => ['required'],
            'free_paid'       => ['required'],
            'status'          => ['required'],
            'lable'           => ['nullable', 'string', 'max:255'],
            'lablebg'         => ['nullable', 'string', 'max:255'],
            'video'           => ['required', 'file', 'mimes:mp4,avi,mov,wmv,flv', 'max:51200'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $Videogif = new Videogif();
        $Videogif->type = $request->type;
        $Videogif->sub_category_id = $request->sub_category_id;
        $Videogif->free_paid = $request->free_paid;
        $Videogif->status = $request->status;
        $Videogif->lable = $request->lable ?? '';
        $Videogif->lablebg = $request->lablebg ?? '';

        if ($request->hasFile('video')) {

            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            // Save video correctly
            $videoPath = $video->storeAs('uploads/videos', $videoName, 'public');
            $Videogif->path = $videoPath;
            // Generate thumbnail
            $thumbPath = $this->generateThumbnail($videoPath, $videoName);
            $Videogif->thumb = $thumbPath;
        }

        $Videogif->save();
        return redirect()->route('videogif.index')->with('success', 'Videogif created successfully.');
    }

    public function edit(Videogif $Videogif)
    {
        $subcategories = SubCategory::where('status', '1')->get();
        return view('videogif.edit', compact('subcategories','Videogif'));
    }

    public function update(Request $request, $id)
    {
        $Videogif = Videogif::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'type'            => ['required'],
            'sub_category_id' => ['required'],
            'free_paid'       => ['required'],
            'status'          => ['required'],
            'lable'           => ['nullable', 'string', 'max:255'],
            'lablebg'         => ['nullable', 'string', 'max:255'],
            'video'           => ['nullable', 'file', 'mimes:mp4,avi,mov,wmv,flv', 'max:51200'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $Videogif->type = $request->type;
        $Videogif->sub_category_id = $request->sub_category_id;
        $Videogif->free_paid = $request->free_paid;
        $Videogif->status = $request->status;
        $Videogif->lable = $request->lable ?? '';
        $Videogif->lablebg = $request->lablebg ?? '';

        if ($request->hasFile('video')) {
            // Delete old video
            if ($Videogif->path && Storage::disk('public')->exists($Videogif->path)) {
                Storage::disk('public')->delete($Videogif->path);
            }
            // Delete old thumbnail
            if ($Videogif->thumb && Storage::disk('public')->exists($Videogif->thumb)) {
                Storage::disk('public')->delete($Videogif->thumb);
            }
            // Upload new video
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs('uploads/videos', $videoName, 'public');
            $Videogif->path = $videoPath;
            // Generate new thumbnail
            $thumbPath = $this->generateThumbnail($videoPath, $videoName);
            $Videogif->thumb = $thumbPath;
        }
        // Save Updated Data
        $Videogif->save();
        return redirect()->route('videogif.index')->with('success', 'Videogif updated successfully.');
    }

    public function destroy($id)
    {
        $Videogif = Videogif::findOrFail($id);

        // Delete video file
        if ($Videogif->path && Storage::disk('public')->exists($Videogif->path)) {
            Storage::disk('public')->delete($Videogif->path);
        }
        // Delete thumbnail file
        if ($Videogif->thumb && Storage::disk('public')->exists($Videogif->thumb)) {
            Storage::disk('public')->delete($Videogif->thumb);
        }

        $Videogif->delete();
        return redirect()->route('videogif.index')->with('success', 'Videogif deleted successfully..');
    }

    private function generateThumbnail($videoPath, $videoName)
    {
        $fullVideoPath = storage_path('app/public/' . $videoPath);
        $thumbName = pathinfo($videoName, PATHINFO_FILENAME) . '_thumb.jpg';
        $thumbPath = 'uploads/thumbs/' . $thumbName;
        $fullThumbPath = storage_path('app/public/' . $thumbPath);
        
        // Create thumbs directory if it doesn't exist
        $thumbDir = dirname($fullThumbPath);
        if (!file_exists($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }
        
        // Generate thumbnail using ffmpeg
        $ffmpegPath = 'C:\ffmpeg\ffmpeg-2025-11-27-git-61b034a47c-essentials_build\bin\ffmpeg.exe';
        $command = "\"$ffmpegPath\" -i \"$fullVideoPath\" -ss 00:00:01 -vframes 1 \"$fullThumbPath\" 2>&1";
        exec($command, $output, $returnCode);
        
        return file_exists($fullThumbPath) ? $thumbPath : null;
    }

    public function updateStatus(Request $request)
    {
        $Videogif = Videogif::find($request->id);
        if (!$Videogif) {
            return response()->json(['success' => false, 'message' => 'Videogif not found']);
        }

        $Videogif->status = $request->status;
        $Videogif->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

}
