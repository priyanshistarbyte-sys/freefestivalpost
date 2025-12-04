<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Illuminate\Http\Request;

class FontController extends Controller
{

    public function index(Request $request)
    {
        return view('fonts.index');
    }
        
    public function create()
    {
        return view('fonts.create');
    }

    public  function store(Request $request)
    {
       //
    }

    public function edit(Font $font)
    {
        return view('fonts.edit',compact('font'));
    }


    public function update(Request $request,Font $font)
    {
        
    }


    public function destroy(Font $font)
    {
        $font->delete();
        return redirect()->route('fonts.index')->with('success', 'Font deleted successfully.');
    }

}
