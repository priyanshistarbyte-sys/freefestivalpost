<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
             $query = Faq::orderBy('id', 'desc');
              return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('question', function ($faq) {
                    return '<div style="max-width: 300px; white-space: normal; word-wrap: break-word;">' . 
                           (strlen($faq->question) > 100 ? substr($faq->question, 0, 100) . '...' : $faq->question) . 
                           '</div>';
                })
                ->addColumn('answer', function ($faq) {
                    return '<div style="max-width: 400px; white-space: normal; word-wrap: break-word;">' . 
                           (strlen($faq->answer) > 150 ? substr($faq->answer, 0, 150) . '...' : $faq->answer) . 
                           '</div>';
                })
                ->addColumn('status', function ($faq) {
                      $checked = $faq->status == 1 ? 'checked' : '';
                        return '
                            <label class = "custom-switch">
                            <input type  = "checkbox" class = "status-toggle" data-id = "'.$faq->id.'" '.$checked.'>
                            <span  class = "switch-slider"></span>
                            </label>';
                })
                ->addColumn('actions', function ($faq) {
                    $buttons  = '';
                    $editUrl  = route('faqs.edit', $faq->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="md"
                            data-title="Edit FAQ" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl  = route('faqs.destroy', $faq->id);
                    $buttons   .= '
                            <button type = "button" class = "btn btn-sm delete-btn"
                                    data-url = "' . $deleteUrl . '"
                                    title = "Delete">
                            <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
            })
            ->rawColumns(['question', 'answer', 'status','actions'])
            ->make(true);
        }
        return view('faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question'  =>  ['required'],
            'answer'    =>  ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $faq            = new Faq();
        $faq->question  = $request->question;
        $faq->answer    = $request->answer;
        $faq->save();

        return redirect()->route('faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        return view('faq.edit',compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        // Validate inputs
         $validator = Validator::make($request->all(), [
            'question'  =>  ['required'],
            'answer'    =>  ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $faq->question = $request->question;
        $faq->answer   = $request->answer;
        $faq->status   = $request->status ?? 0;
        $faq->save();

        return redirect()->route('faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('faqs.index')->with('success', 'FAQ deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $faq = Faq::find($request->id);
        if (!$faq) {
            return response()->json(['success' => false, 'message' => 'FAQ not found.']);
        }
        $faq->status = $request->status;
        $faq->save();
        return response()->json(['success' => true, 'message' => 'FAQ updated successfully.']);
    }
}
