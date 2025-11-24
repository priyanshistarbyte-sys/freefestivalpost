<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         if ($request->ajax()) {
            $query = MainCategory::with(['category'])->orderBy('mid', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function ($category) {
                    return $category->category ? $category->category->title : '-';
                })
                ->filterColumn('category', function($query, $keyword) {
                    $query->whereHas('category', function($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('image', function ($category) {
                    $imagePath = $category->image 
                        ? asset('storage/' . ltrim($category->image, '/')) 
                        : asset('assets/images/defaultApp.png');
                    return '<img src="'.$imagePath.'" alt="Icon" class="dataTable-app-img rounded" width="40" height="40">';
                })
                ->addColumn('noti_banner', function ($category) {
                    if($category->noti_banner)
                    {
                        $bannerPath = $category->noti_banner 
                                            ? asset('storage/' . ltrim($category->noti_banner, '/')) 
                                            : asset('assets/images/defaultApp.png');
                       return '<img src="'.$bannerPath.'" alt="Icon" class="dataTable-app-img rounded" width="40" height="40">';
                    }
                    
                })
                ->addColumn('status', function ($category) {
                    $checked = $category->status == 1 ? 'checked' : '';
                    return '<label class="custom-switch">
                                <input type="checkbox" disabled '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->addColumn('actions', function ($category) {
                     $buttons = '';
                     $editUrl = route('main-category.edit', $category->mid);
                     $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-2">
                                <i class="fa fa-edit me-2"></i> Edit
                             </a>
                            ';
                     $deleteUrl = route('main-category.destroy', $category->mid);
                     $buttons .= '
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> Delete
                            </button>
                            ';
                       
                    return $buttons;
                })
                ->rawColumns(['category','image','noti_banner','status','actions'])
                ->make(true);
        }
       return view('main-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('main-category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
             'mtitle'  => ['required', 'string', 'max:255'],
             'image'   => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $path_image = $request->file('image')->store('uploads/images/main_category_image', 'public');
        if($request->hasFile('noti_banner'))
        {
            $path_banner = $request->file('noti_banner')->store('uploads/images/main_category_banner', 'public');
        }
        $category = new MainCategory();
        $category->image = $path_image ?? '';
        $category->category_id = $request->category_id;
        $category->mtitle = $request->mtitle;
        $category->mslug = MainCategory::slug_string($request->mtitle);
        $category->event_date = $request->event_date ?: null;
        $category->status = $request->status ?? 0;
        $category->plan_auto = $request->plan_auto;
        $category->lable = $request->lable ?? '';
        $category->lablebg = $request->label_bg ?? '';
        $category->noti_banner = $path_banner ?? '';
        $category->noti_quote = $request->noti_quote ?? '';
        $category->mask = $request->mask ?? '';
        $category->save();

        return redirect()->route('main-category.index')->with('success', 'Main Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MainCategory $mainCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MainCategory $mainCategory)
    {
        $categories = Category::get();
        return view('main-category.edit',compact('categories','mainCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = MainCategory::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
             'mtitle'  => ['required', 'string', 'max:255'],
             'image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

       
        if ($request->hasFile('image')) {

            // Delete old image
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            // Save new image
            $path_image = $request->file('image')->store('uploads/images/main_category_image', 'public');
            $category->image = $path_image;
        }

        if ($request->hasFile('noti_banner')) {
            // Delete old banner
            if ($category->noti_banner && Storage::disk('public')->exists($category->noti_banner)) {
                Storage::disk('public')->delete($category->noti_banner);
            }

            // Save new banner
            $path_banner = $request->file('noti_banner')->store('uploads/images/main_category_banner', 'public');
            $category->noti_banner = $path_banner;
        }

        // Update fields
        $category->category_id = $request->category_id;
        $category->mtitle = $request->mtitle;

        // Update slug only if title changed
        if ($request->mtitle !== $category->mtitle) {
            $category->mslug = MainCategory::slug_string($request->mtitle);
        }

        // event_date optional (and will not be NULL violation if DB allows null)
        $category->event_date = $request->event_date ?: null;

        $category->status = $request->status ?? 0;
        $category->plan_auto = $request->plan_auto;
        $category->lable = $request->lable ?? '';
        $category->lablebg = $request->label_bg ?? '';
        $category->noti_quote = $request->noti_quote ?? '';
        $category->mask = $request->mask ?? '';

        $category->save();

        return redirect()->route('main-category.index')->with('success', 'Main Category updated successfully.');
    }


    /**
     * Toggle status of the specified resource.
     */
    public function toggleStatus(Request $request, $id)
    {
        $category = MainCategory::findOrFail($id);
        $category->status = $category->status == 1 ? 0 : 1;
        $category->save();
        
        return response()->json(['success' => true, 'status' => $category->status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = MainCategory::where('mid', $id)->firstOrFail();
        $category->delete();
        return redirect()->route('main-category.index')->with('success', 'Main Category deleted successfully.');
    }
}
