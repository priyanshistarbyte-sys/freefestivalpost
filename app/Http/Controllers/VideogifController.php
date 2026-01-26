<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Videogif;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\App;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;


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

                    $videoPath = asset('storage/' . $Videogif->path);

                    return '
                        <video width="160" height="90" controls preload="metadata">
                            <source src="'.$videoPath.'" type="video/mp4">
                        </video>
                    ';
                })
                ->addColumn('thumb', function ($Videogif) {
                    $imagePath = $Videogif->thumb
                        ? asset('storage/' . ltrim($Videogif->thumb, '/'))
                        : asset('assets/images/default.jpg');
                   
                    return '
                    <a class="image-popup-no-margins" href="' . $imagePath . '">
						<img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">
					</a>
                    ';
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
        $request->validate([
            'type'            => 'required',
            'sub_category_id' => 'required',
            'free_paid'       => 'required',
            'status'          => 'required',
            'video'           => 'required|file|mimes:mp4,avi,mov,wmv,flv|max:51200',
        ]);

        $videogif = new Videogif();
        $videogif->type = $request->type;
        $videogif->sub_category_id = $request->sub_category_id;
        $videogif->free_paid = $request->free_paid;
        $videogif->status = $request->status;
        $videogif->lable = $request->lable ?? '';
        $videogif->lablebg = $request->lablebg ?? '';
        $videogif->save(); // FIRST save → get ID

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . $video->getClientOriginalName();
            $videoPath = $video->storeAs('uploads/videos', $videoName, 'public');

            $videogif->path = $videoPath;

            // generate thumbnail
            $thumb = $this->generateThumbnail($videoPath, $videogif->id);
            $videogif->thumb = $thumb;
        }

        $videogif->save();

        return redirect()
            ->route('videogif.index')
            ->with('success', 'Videogif created successfully.');
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

  
    // private function generateThumbnail($videoPath)
    // {
    //     $ffmpeg = FFMpeg::create();
    //     $video = $ffmpeg->open(storage_path('app/public/' . $videoPath));

    //     $thumbName = pathinfo($videoPath, PATHINFO_FILENAME) . '_thumb.jpg';
    //     $thumbPath = 'uploads/thumbs/' . $thumbName;

    //     // Ensure directory exists
    //     Storage::disk('public')->makeDirectory('uploads/thumbs');

    //     $video
    //         ->frame(TimeCode::fromSeconds(1))
    //         ->save(storage_path('app/public/' . $thumbPath));

    //     return $thumbPath;
    // }
    private function generateThumbnail(string $videoPath, int $id)
    {
        // ---------- LOCAL ENV ----------
        if (App::environment('local')) {

            try {
                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open(storage_path('app/public/' . $videoPath));

                $thumbName = $id . '_thumb.jpg';
                $thumbPath = 'uploads/thumbs/' . $thumbName;

                Storage::disk('public')->makeDirectory('uploads/thumbs');

                $video
                    ->frame(TimeCode::fromSeconds(8))
                    ->save(storage_path('app/public/' . $thumbPath));

                return $thumbPath;

            } catch (\Exception $e) {
                logger()->error('Local FFmpeg error: ' . $e->getMessage());
                return null;
            }
        }

        // ---------- PRODUCTION ENV ----------
        if (App::environment('production')) {

            $ffmpegPath = '/usr/bin/ffmpeg';
            $input = storage_path('app/public/' . $videoPath);

            $thumbDir = storage_path('app/public/uploads/videos/thumb');
            if (!file_exists($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            $thumbName = $id . '.jpg';
            $output = $thumbDir . '/' . $thumbName;

            $command = "$ffmpegPath -y -i \"$input\" -ss 6 -vframes 1 -s 336x336 \"$output\" 2>&1";
            shell_exec($command);

            return file_exists($output)
                ? 'uploads/videos/thumb/' . $thumbName
                : null;
        }

        return null;
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
