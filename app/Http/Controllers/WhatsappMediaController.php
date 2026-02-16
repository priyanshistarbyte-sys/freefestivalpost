<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class WhatsappMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = WhatsappMedia::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('image', function ($whatsappMedia) {
                    $imagePath = $whatsappMedia->image
                        ? asset('storage/' . ltrim($whatsappMedia->image, '/'))
                        :  asset('assets/images/default.jpg');
                    return '
                    <a class="image-popup-no-margins" href="' . $imagePath . '">
						<img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="20" height="20">
					</a>
                    ';
                })
                ->addColumn('actions', function ($whatsappMedia) {
                    $buttons  = '';
                    $editUrl  = route('whatsapp-media.edit', $whatsappMedia->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Whatsapp Media" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('whatsapp-media.delete', $whatsappMedia->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['image','actions'])
                ->make(true);
        }
        return view('whatsapp-media.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('whatsapp-media.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $path_image               = $request->file('image')->store('uploads/images/whatsapp_media', 'public');
        $whatsapp_media           = new WhatsappMedia();
        $whatsapp_media->image    = $path_image ?? '';
        $whatsapp_media->title    = $request->title ?? null;
        $whatsapp_media->save();

        return redirect()->route('whatsapp-media.index')->with('success', 'Whatsapp Media created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsappMedia $whatsappMedia)
    {
         return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $whatsappMedia = WhatsappMedia::findOrFail($id);
        return view('whatsapp-media.edit', compact('whatsappMedia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $whatsappMedia = WhatsappMedia::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $data = [
            'title' => $request->title,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($whatsappMedia->image && Storage::disk('public')->exists($whatsappMedia->image)) {
                Storage::disk('public')->delete($whatsappMedia->image);
            }
            // Upload new image
            $data['image'] = $request->file('image')->store('uploads/images/whatsapp_media', 'public');
        }
        $whatsappMedia->update($data);
        return redirect()->route('whatsapp-media.index')->with('success', 'Whatsapp Media updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $whatsappMedia = WhatsappMedia::findOrFail($id);
        // Delete image
        if ($whatsappMedia->image && Storage::disk('public')->exists($whatsappMedia->image)) {
            Storage::disk('public')->delete($whatsappMedia->image);
        }
        $whatsappMedia->delete();
        return redirect()->route('whatsapp-media.index')->with('success', 'Whatsapp Media deleted successfully.');
    }
}
