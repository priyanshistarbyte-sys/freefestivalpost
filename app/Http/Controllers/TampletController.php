<?php

namespace App\Http\Controllers;

use App\Models\Font;
use App\Models\SubCategory;
use App\Models\Tamplet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TampletController extends Controller
{
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = Tamplet::with(['category'])->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function ($tamplet) {
                        return $tamplet->category ? $tamplet->category->mtitle : '-';
                })
                ->filterColumn('category', function($query, $keyword) {
                    $query->whereHas('category', function($q) use ($keyword) {
                        $q->where('mtitle', 'like', "%{$keyword}%");
                    });
                })
                ->editColumn('event_date', function ($tamplet) {
                    return $tamplet->event_date ? with(new \Carbon\Carbon($tamplet->event_date))->format('d-m-Y') : '';
                })
                ->addColumn('image', function ($tamplet) {
                    $imagePath = $tamplet->image
                        ? asset('storage/' . ltrim($tamplet->image, '/'))
                        : asset('assets/images/defaultApp.png');
                    return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="30">';
                })
                ->addColumn('mask', function ($tamplet) {
                    if(!$tamplet->planImgName){
                        return '<span class="badge bg-danger">No Mask</span>';
                    }else{
                        $imagePath = $tamplet->planImgName
                            ? asset('storage/' . ltrim($tamplet->planImgName, '/'))
                            : asset('assets/images/defaultApp.png');
                        return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="30">';
                    }
                })
                ->addColumn('free_paid', function ($tamplet) {
                    if ($tamplet->free_paid == 1) {
                        $icon = asset('assets/images/paid.svg');
                        return '<img src="'.$icon.'" alt="Paid" width="20">';
                    } else {
                        return '<span class="badge bg-success">Free</span>';
                    }
                })
                ->addColumn('actions', function ($tamplet) {
                    $buttons = '';
                    $editUrl = route('tamplet.edit', $tamplet->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                             </a>
                            ';
                    $deleteUrl = route('tamplet.destroy', $tamplet->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> 
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['category','image','mask','free_paid','actions'])
                ->make(true);
        }
        return view('tamplet.index');
        
    }

    public function create()
    {
        $categories = SubCategory::where('status','1')->get();
        $fonts      = Font::get();
        return view('tamplet.create',compact('categories','fonts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_category_id' => ['required'],
            'image.*'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'mask'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $tamplet                  = new Tamplet();
        $tamplet->sub_category_id = $request->sub_category_id;
        $tamplet->font_color      = $request->font_color;
        $tamplet->lablebg         = $request->lable_bg;
        $tamplet->font_size       = $request->font_size;
        $tamplet->lable           = $request->label_new;
        $tamplet->font_type       = $request->font_type;
        $tamplet->language        = $request->language;
        $tamplet->event_date      = $request->event_date;
        $tamplet->free_paid       = $request->free_paid ? 1 : 0;
        $tamplet->type            = 1;// if you use type, set default or dynamic
        $imagePaths               = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $imgName      = time() . '_' . $img->getClientOriginalName();
                $stored       = $img->storeAs('uploads/tamplet/images', $imgName, 'public');
                $imagePaths[] = $stored;
            }
        }
        $tamplet->path = json_encode($imagePaths);

        if ($request->has('has_mask') && $request->hasFile('mask')) {
            $mask                 = $request->file('mask');
            $maskName             = time() . '_' . $mask->getClientOriginalName();
            $maskPath             = $mask->storeAs('uploads/tamplet/masks', $maskName, 'public');
            $tamplet->planImgName = $maskPath;
        } else {
            $tamplet->planImgName = null;
        }
        $tamplet->save();
        return redirect()->route('tamplet.index')->with('success', 'Tamplet created successfully.');
    }

    public function show(Tamplet $tamplet)
    {
        //
    }

    public function edit(Tamplet $tamplet)
    {
        $categories = SubCategory::where('status','1')->get();
        $fonts      = Font::get();
        return view('tamplet.edit', compact('tamplet','categories','fonts'));
    }

    public function update(Request $request, Tamplet $tamplet)
    {
        $validator = Validator::make($request->all(), [
            'type'            => ['required'],
            'image.*'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'mask'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        $tamplet->sub_category_id = $request->type;
        $tamplet->font_color      = $request->font_color;
        $tamplet->lablebg         = $request->lablebg;
        $tamplet->font_size       = $request->font_size;
        $tamplet->lable           = $request->label_new;
        $tamplet->font_type       = $request->font_type;
        $tamplet->language        = $request->ln_post;
        $tamplet->event_date      = $request->event_date;
        $tamplet->free_paid       = $request->has('free_paid') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            $imagePaths = $tamplet->path ? json_decode($tamplet->path, true) : [];
            foreach ($request->file('image') as $img) {
                $imgName      = time() . '_' . $img->getClientOriginalName();
                $stored       = $img->storeAs('uploads/tamplet/images', $imgName, 'public');
                $imagePaths[] = $stored;
            }
            $tamplet->path = json_encode($imagePaths);
        }
        
        if ($request->hasFile('mask')) {
            $mask                 = $request->file('mask');
            $maskName             = time() . '_' . $mask->getClientOriginalName();
            $maskPath             = $mask->storeAs('uploads/tamplet/masks', $maskName, 'public');
            $tamplet->planImgName = $maskPath;
        } elseif (!$request->has('has_mask')) {
            $tamplet->planImgName = null;
        }
        
        $tamplet->save();
        return redirect()->route('tamplet.index')->with('success', 'Tamplet updated successfully.');
    }
  
    public function destroy($id)
    {
        $tamplet = Tamplet::findOrFail($id);

        // Delete video file
        if ($tamplet->path) {
            $images = json_decode($tamplet->path, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        // Delete planImgName file
        if ($tamplet->planImgName && Storage::disk('public')->exists($tamplet->planImgName)) {
            Storage::disk('public')->delete($tamplet->planImgName);
        }

        $tamplet->delete();
        return redirect()->route('tamplet.index')->with('success', 'Tamplet deleted successfully..');
    }
}