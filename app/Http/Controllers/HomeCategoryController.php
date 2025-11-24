<?php

namespace App\Http\Controllers;

use App\Models\HomeCategory;
use App\Models\MainCategory;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HomeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = HomeCategory::with(['category'])->orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('category', function ($category) {
                    return $category->category ? $category->category->mtitle : '-';
                })
                ->addColumn('status', function ($category) {
                      $checked = $category->status == 1 ? 'checked' : '';
                        return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$category->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                    })
                ->addColumn('is_show_on_home', function ($category) {
                    $checked = $category->is_show_on_home == 1 ? 'checked' : '';
                      return '
                            <label class="custom-switch">
                                <input type="checkbox" class="home-status-toggle" data-id="'.$category->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->addColumn('is_new', function ($category) {
                    $checked = $category->is_new == 1 ? 'checked' : '';
                    return '<label class="custom-switch">
                                <input type="checkbox" disabled ' . $checked . '>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->addColumn('actions', function ($category) {
                    $buttons = '';
                    $editUrl = route('home-category.edit', $category->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm btn-primary me-2" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Category" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>Edit
                            </a>
                            ';
                    $deleteUrl = route('home-category.destroy', $category->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> Delete
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['category', 'status', 'is_show_on_home', 'is_new', 'actions'])
                ->make(true);
        }
        return view('home-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SubCategory::get();
        return view('home-category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
            'sub_category_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $homeCategory = new HomeCategory();
        $homeCategory->title = $request->title;
        $homeCategory->sub_category_id = $request->sub_category_id;
        $homeCategory->status = $request->status ?? 0;
        $homeCategory->sequence = $request->sequence ?? 0;
        $homeCategory->is_show_on_home  = $request->is_show_on_home ?? 0;
        $homeCategory->is_new = $request->is_new ?? 0;
        $homeCategory->save();

        return redirect()->route('home-category.index')->with('success', 'Home Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(HomeCategory $homeCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HomeCategory $homeCategory)
    {
        $categories = SubCategory::get();
        return view('home-category.edit', compact('categories', 'homeCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HomeCategory $homeCategory)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
            'sub_category_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $homeCategory->title = $request->title;
        $homeCategory->sub_category_id = $request->sub_category_id;
        $homeCategory->status = $request->status ?? 0;
        $homeCategory->sequence = $request->sequence ?? 0;
        $homeCategory->is_show_on_home  = $request->is_show_on_home ?? 0;
        $homeCategory->is_new = $request->is_new ?? 0;
        $homeCategory->save();

        return redirect()->route('home-category.index')->with('success', 'Home Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HomeCategory $homeCategory)
    {
        $homeCategory->delete();
        return redirect()->route('home-category.index')->with('success', 'Home Category deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $homeCategory = HomeCategory::find($request->id);
        if (!$homeCategory) {
            return response()->json(['success' => false, 'message' => 'Home Category not found']);
        }

        $homeCategory->status = $request->status;
        $homeCategory->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    public function showHome(Request $request)
    {
        $homeCategory = HomeCategory::find($request->id);
        if (!$homeCategory) {
            return response()->json(['success' => false, 'message' => 'Home Category not found']);
        }

        $homeCategory->is_show_on_home = $request->is_show_on_home;
        $homeCategory->save();

        return response()->json(['success' => true, 'message' => 'Show on home updated successfully']);
    }
    
}
