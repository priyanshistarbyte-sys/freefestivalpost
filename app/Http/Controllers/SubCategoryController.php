<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SubCategory::with(['category'])->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function ($subCategory) {
                    return $subCategory->category ? $subCategory->category->title : '-';
                })
             
                ->filterColumn('category', function ($query, $keyword) {
                    $query->whereHas('category', function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })
                ->addColumn('image', function ($subCategory) {
                    $imagePath = $subCategory->image
                        ? asset('storage/' . ltrim($subCategory->image, '/'))
                        : asset('assets/images/defaultApp.png');
                    return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="40" height="40">';
                })
                ->addColumn('noti_banner', function ($subCategory) {
                    if ($subCategory->noti_banner) {
                        $bannerPath = $subCategory->noti_banner
                            ? asset('storage/' . ltrim($subCategory->noti_banner, '/'))
                            : asset('assets/images/defaultApp.png');
                        return '<img src="' . $bannerPath . '" alt="Icon" class="dataTable-app-img rounded" width="40" height="40">';
                    }
                })
                ->addColumn('status', function ($subCategory) {
                       $checked = $subCategory->status == 1 ? 'checked' : '';
                        return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$subCategory->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                    })
                ->addColumn('actions', function ($subCategory) {
                    $buttons = '';
                    $editUrl = route('sub-category.edit', $subCategory->id);
                    $buttons .= '
                             <a href="' . $editUrl . '" class="btn btn-sm">
                                <i class="fa fa-edit me-2"></i>
                             </a>
                            ';
                    $deleteUrl = route('sub-category.destroy', $subCategory->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> 
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['category', 'image', 'noti_banner', 'status', 'actions'])
                ->make(true);
        }
        return view('sub-category.index');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();
        return view('sub-category.create', compact('categories'));
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

        $path_image = $request->file('image')->store('uploads/images/sub_category_image', 'public');
        if ($request->hasFile('noti_banner')) {
            $path_banner = $request->file('noti_banner')->store('uploads/images/sub_category_banner', 'public');
        }

        $subCategory = new SubCategory();
        $subCategory->image = $path_image ?? '';
        $subCategory->category_id = $request->category_id;
        $subCategory->is_parent = $request->is_parent ?? 0;
        $subCategory->parent_category = $request->is_parent == 1 ? $request->parent_category : null;
        $subCategory->mtitle = $request->mtitle;
        $subCategory->mslug = SubCategory::slug_string($request->mtitle);
        $subCategory->event_date = $request->event_date ?: null;
        $subCategory->status = $request->status ?? 0;
        $subCategory->plan_auto = $request->plan_auto;
        $subCategory->lable = $request->label ?? '';
        $subCategory->lablebg = $request->label_bg ?? '';
        $subCategory->noti_banner = $path_banner ?? '';
        $subCategory->noti_quote = $request->noti_quote ?? '';
        $subCategory->mask = $request->mask ?? '';
        $subCategory->save();

        // IF PARENT CATEGORY SELECTED → UPDATE ITS is_child
        if ($request->is_parent == 1 && $request->parent_category) {
            SubCategory::where('id', $request->parent_category)
                ->update(['is_child' => $subCategory->id]);
        }

        // IF PARENT CATEGORY SELECTED → UPDATE ITS is_child
        if ($request->category_id) {
            Category::where('id', $request->category_id)
                ->update(['sub' => '1']);
        }

        return redirect()->route('sub-category.index')->with('success', 'Sub Category created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
       
        $categories = Category::all();

        $parentSubs = SubCategory::where('category_id', $subCategory->category_id)
            ->where('is_parent', 0)
            ->get();

        return view('sub-category.edit', compact('subCategory', 'categories', 'parentSubs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'mtitle'  => ['required', 'string', 'max:255'],
            'image'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }


        // Update slug only if title changed
        if ($request->mtitle !== $subCategory->mtitle) {
            $subCategory->mslug = SubCategory::slug_string($request->mtitle);
        }

        if ($request->hasFile('image')) {

            // Delete old image
            if ($subCategory->image && Storage::disk('public')->exists($subCategory->image)) {
                Storage::disk('public')->delete($subCategory->image);
            }

            // Save new image
            $path_image = $request->file('image')->store('uploads/images/sub_category_image', 'public');
            $subCategory->image = $path_image;
        }

        if ($request->hasFile('noti_banner')) {
            // Delete old banner
            if ($subCategory->noti_banner && Storage::disk('public')->exists($subCategory->noti_banner)) {
                Storage::disk('public')->delete($subCategory->noti_banner);
            }

            // Save new banner
            $path_banner = $request->file('noti_banner')->store('uploads/images/sub_category_banner', 'public');
            $subCategory->noti_banner = $path_banner;
        }
      
            
        // Update fields
        $subCategory->category_id = $request->category_id;
        $subCategory->mtitle = $request->mtitle;
        $subCategory->is_parent = $request->parent_category ? 1 : 0;
        $subCategory->parent_category = $request->parent_category ?: null;
        $subCategory->event_date = $request->event_date ?: null;
        $subCategory->status = $request->status ?? 0;
        $subCategory->plan_auto = $request->plan_auto;
        $subCategory->lable = $request->lable ?? '';
        $subCategory->lablebg = $request->label_bg ?? '';
        $subCategory->noti_quote = $request->noti_quote ?? '';
        $subCategory->mask = $request->mask ?? '';

        $subCategory->save();

         // IF PARENT CATEGORY SELECTED → UPDATE ITS is_child
        if ($request->is_parent == 1 && $request->parent_category) {
            SubCategory::where('id', $request->parent_category)
                ->update(['is_child' => $subCategory->id]);
        }

        return redirect()->route('sub-category.index')->with('success', 'Sub Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('category.index')->with('success', 'Sub Category deleted successfully.');
    }

    public function getSubcategories($cid)
    {
        $subcategories = SubCategory::where('category_id', $cid)
            ->where('is_parent', 0)
            ->get();

        return response()->json($subcategories);
    }

    public function updateStatus(Request $request)
    {
        $subCategory = SubCategory::find($request->id);
        if (!$subCategory) {
            return response()->json(['success' => false, 'message' => 'Sub Category not found']);
        }

        $subCategory->status = $request->status;
        $subCategory->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
