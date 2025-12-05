<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FontController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Font::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('actions', function ($font) {
                    $buttons  = '';
                    $editUrl  = route('fonts.edit', $font->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit Font" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl  = route('fonts.destroy', $font->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('fonts.index');
    }

    public function create()
    {
        return view('fonts.create');
    }

    public function store(Request $request)
    {
       
        // Validate inputs
        $validator = Validator::make($request->all(), [
            'font_name' => ['required', 'file'],
        ]);
        
        // Additional validation for font file extensions
        $file = $request->file('font_name');
        if ($file) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, ['ttf', 'otf', 'woff', 'woff2'])) {
                return redirect()->back()->with('error', 'Please upload a valid font file (.ttf, .otf, .woff, .woff2)');
            }
        }

         if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Original file name
        $originalName = $file->getClientOriginalName();

        // Store file
        $storedPath = $file->store('uploads/images/fonts', 'public');

        // Save to DB
        Font::create([
            'font_name' => $originalName,   // save readable name
            'file_path' => $storedPath,     // save actual storage path
        ]);

        return redirect()->route('fonts.index')->with('success', 'Font uploaded successfully.');
    }


    public function edit(Font $font)
    {
        return view('fonts.edit',compact('font'));
    }


    public function update(Request $request, Font $font)
    {
        // If new font file is uploaded
        if ($request->hasFile('font_name')) {
            $file = $request->file('font_name');
            $extension = strtolower($file->getClientOriginalExtension());
            
            if (!in_array($extension, ['ttf', 'otf', 'woff', 'woff2'])) {
                return redirect()->back()->with('error', 'Please upload a valid font file (.ttf, .otf, .woff, .woff2)');
            }
            
            // Delete old file if exists
            if ($font->file_path && Storage::disk('public')->exists($font->file_path)) {
                Storage::disk('public')->delete($font->file_path);
            }
            
            $originalName = $file->getClientOriginalName();
            $storedPath = $file->store('uploads/images/fonts', 'public');
            
            $font->update([
                'font_name' => $originalName,
                'file_path' => $storedPath,
            ]);
        }
        
        return redirect()->route('fonts.index')->with('success', 'Font updated successfully.');
    }


    public function destroy($id)
    {
        $font = Font::findOrFail($id);

        // Delete image
        if ($font->file_path && Storage::disk('public')->exists($font->file_path)) {
            Storage::disk('public')->delete($font->file_path);
        }

        $font->delete();
        return redirect()->route('fonts.index')->with('success', 'Font deleted successfully.');
    }

}
