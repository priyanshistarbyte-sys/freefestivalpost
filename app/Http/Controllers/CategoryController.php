<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('icon', function ($category) {
                    $imagePath = $category->icon
                        ? asset('storage/' . ltrim($category->icon, '/'))
                        : asset('assets/images/defaultApp.png');
                    return '<img src="' . $imagePath . '" alt="Icon" class="dataTable-app-img rounded" width="40" height="40">';
                })
                ->addColumn('actions', function ($category) {
                    $buttons = '';
                    $editUrl = route('category.edit', $category->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm btn-primary me-2" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Category" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>Edit
                            </a>
                            ';
                    $deleteUrl = route('category.destroy', $category->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i> Delete
                            </button>
                            ';

                    return $buttons;
                })
                ->rawColumns(['icon', 'actions'])
                ->make(true);
        }
        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
            'icon'   => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $path_icon = $request->file('icon')->store('uploads/images/category_icon', 'public');
        $category = new Category();
        $category->title = $request->title;
        $category->icon = $path_icon ?? '';
        $category->sort = $request->sort ?? 0;
        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        
        $validator = Validator::make($request->all(), [
            'title'  => ['required', 'string', 'max:255'],
            'icon'   => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $data = [
            'title' => $request->title,
            'sort'      => $request->sort ?? 0,
        ];
        if ($request->hasFile('icon')) {
            // Delete old icon if exists
            if ($category->icon && Storage::disk('public')->exists($category->icon)) {
                Storage::disk('public')->delete($category->icon);
            }

            // Upload new icon
            $data['icon'] = $request->file('icon')->store('uploads/images/category_icon', 'public');
        }
        $category->update($data);
        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
  
    public function destroy(Category $category)
    {
         $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
