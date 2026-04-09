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
                    $imagePath = $tamplet->path
                        ? asset('storage/' . ltrim($tamplet->path, '/'))
                        : asset('assets/images/default.jpg');
                    return '
                    <a class="image-popup-no-margins" href="' . $imagePath . '">
						<img class="img-responsive" src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="30" height="20">
					</a>
                    ';
                })
                ->addColumn('mask', function ($tamplet) {
                    $masks = $tamplet->planImgName;
                    if (empty($masks)) {
                        return '<span class="badge bg-danger">No Mask</span>';
                    }
                    $html = '<div class="d-flex flex-wrap gap-1">';
                    foreach ($masks as $mask) {
                        $url = asset('storage/' . trim($mask));
                        $html .= '<a href="' . $url . '" target="_blank">
                                    <img src="' . $url . '" width="35" height="35" style="object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                                  </a>';
                    }
                    $html .= '</div>';
                    return $html;
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
            'mask.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $subCategory = SubCategory::find($request->sub_category_id);
        $mslug = $subCategory ? $subCategory->mslug : 'default';

        // ✅ STEP 1: STORE ALL MASKS IN ARRAY
        $maskPaths = [];

        if ($request->has('has_mask') && $request->hasFile('mask')) {
            foreach ($request->file('mask') as $index => $mask) {
                $maskPaths[$index] = $mask->storeAs('uploads/tamplet/masks', $mask->getClientOriginalName(), 'public');
            }
        }

        // ✅ STEP 2: STORE IMAGES + ASSIGN ALL MASKS AS JSON
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $index => $img) {

                $tamplet = new Tamplet();
                $tamplet->sub_category_id = $request->sub_category_id;
                $tamplet->font_color      = $request->font_color;
                $tamplet->lablebg         = $request->lable_bg;
                $tamplet->font_size       = $request->font_size;
                $tamplet->lable           = $request->label;
                $tamplet->font_type       = $request->font_type;
                $tamplet->language        = $request->language;
                $tamplet->event_date      = $request->event_date;
                $tamplet->event           = $request->event ? 1 : 0;
                $tamplet->free_paid       = $request->free_paid ? 1 : 0;
                $tamplet->has_mask        = $request->has('has_mask') ? 1 : 0;

                // Store all mask paths as array (model cast handles JSON encoding)
                $tamplet->planImgName = !empty($maskPaths) ? array_values($maskPaths) : null;

                $imgName = $mslug . '_' . time() . '_' . $index . '_' . $img->getClientOriginalName();
                $stored  = $img->storeAs('uploads/tamplet/images', $imgName, 'public');

                $tamplet->path = $stored;
                $tamplet->save();
            }
        }

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
    //    dd($request->all());
        $validator = Validator::make($request->all(), [
            'sub_category_id'            => ['required'],
            'image.*'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'mask.*'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        
        $tamplet->sub_category_id = $request->sub_category_id;
        $tamplet->font_color      = $request->font_color;
        $tamplet->lablebg         = $request->lablebg;
        $tamplet->font_size       = $request->font_size;
        $tamplet->lable           = $request->label_new;
        $tamplet->font_type       = $request->font_type;
        $tamplet->language        = $request->ln_post;
        $tamplet->event_date      = $request->event_date;
        $tamplet->event           = $request->has('event') ? 1 : 0; 
        $tamplet->free_paid       = $request->has('free_paid') ? 1 : 0;
      
        
        if ($request->hasFile('image')) {
            if ($tamplet->path) {
                Storage::disk('public')->delete($tamplet->path);
            }
            $subCategory = SubCategory::find($request->type);
            $mslug = $subCategory ? $subCategory->mslug : 'default';
            $img           = $request->file('image');
            $imgName       = $mslug . '_' . time() . '_' . $img->getClientOriginalName();
            $stored        = $img->storeAs('uploads/tamplet/images', $imgName, 'public');
            $tamplet->path = $stored;
        }
        
        $tamplet->has_mask = $request->has('has_mask') ? 1 : 0;

        if ($request->has('has_mask') && $request->hasFile('mask')) {
            $maskPaths = $tamplet->planImgName ?? [];
            foreach ($request->file('mask') as $index => $mask) {
                $maskPaths[] = $mask->storeAs('uploads/tamplet/masks', $mask->getClientOriginalName(), 'public');
            }
            $tamplet->planImgName = array_values($maskPaths);
        } elseif (!$request->has('has_mask')) {
            foreach ($tamplet->planImgName ?? [] as $oldMask) {
                Storage::disk('public')->delete($oldMask);
            }
            $tamplet->planImgName = null;
        }
        
        $tamplet->save();
        return redirect()->route('tamplet.index')->with('success', 'Tamplet updated successfully.');
    }
  
    public function destroy($id)
    {
        $tamplet = Tamplet::findOrFail($id);

        // Delete image file
        if ($tamplet->path) {
            Storage::disk('public')->delete($tamplet->path);
        }
        // Delete planImgName files
        $masks = is_array($tamplet->planImgName) ? $tamplet->planImgName : ($tamplet->planImgName ? [$tamplet->planImgName] : []);
        foreach ($masks as $mask) {
            if ($mask && Storage::disk('public')->exists($mask)) {
                Storage::disk('public')->delete($mask);
            }
        }

        $tamplet->delete();
        return redirect()->route('tamplet.index')->with('success', 'Tamplet deleted successfully..');
    }
}