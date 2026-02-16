<?php

namespace App\Http\Controllers;

use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class WhatsappTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
           if ($request->ajax()) {
            $query = WhatsappTemplate::orderBy('id', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('template_details', function ($row) {
                    return '
                        <b>Title:</b> '.$row->tamp_name.'<br>
                        <b>Name:</b> '.$row->template.'<br>
                        <b>Type:</b> '.$row->type.'<br>
                        <b>Language:</b> '.$row->lang.'<br>
                        <b>Parameter:</b> '.$row->param.'
                    ';
                })
                ->filterColumn('template_details', function($query, $keyword) {
                    $query->where(function($q) use ($keyword) {

                        $q->where('tamp_name', 'like', "%{$keyword}%")
                        ->orWhere('template', 'like', "%{$keyword}%")
                        ->orWhere('type', 'like', "%{$keyword}%")
                        ->orWhere('lang', 'like', "%{$keyword}%")
                        ->orWhere('param', 'like', "%{$keyword}%");

                    });
                })
                ->addColumn('status', function ($whatsappTemplate) {
                    $checked = $whatsappTemplate->status == 1 ? 'checked' : '';
                      return '
                            <label class="custom-switch">
                                <input type="checkbox" class="status-toggle" data-id="'.$whatsappTemplate->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
            
                ->addColumn('bulk_status', function ($whatsappTemplate) {
                    $checked = $whatsappTemplate->bulk_status == 1 ? 'checked' : '';
                      return '
                            <label class="custom-switch">
                                <input type="checkbox" class="bulk-status-toggle" data-id="'.$whatsappTemplate->id.'" '.$checked.'>
                                <span class="switch-slider"></span>
                            </label>';
                })
                ->editColumn('created_at', function ($whatsappTemplate) {
                    return $whatsappTemplate->created_at ? with(new \Carbon\Carbon($whatsappTemplate->created_at))->format('d-m-Y') : '';
                })
            
                ->addColumn('actions', function ($whatsappTemplate) {
                    $buttons  = '';
                    $editUrl  = route('whatsapp-template.edit', $whatsappTemplate->id);
                    $buttons .= '
                            <a href="#" class="btn btn-sm" 
                            data-ajax-popup="true" data-size="lg"
                            data-title="Edit Whatsapp Template" data-url="' . $editUrl . '"
                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                <i class="fa fa-edit me-2"></i>
                            </a>
                            ';
                    $deleteUrl = route('whatsapp-template.delete', $whatsappTemplate->id);
                    $buttons .= '
                            <button type="button" class="btn btn-sm delete-btn"
                                data-url="' . $deleteUrl . '"
                                title="Delete">
                                <i class="fa fa-trash me-2"></i>
                            </button>
                            ';
                    return $buttons;
                })
                ->rawColumns(['template_details','status','bulk_status','created_at','actions'])
                ->make(true);
        }
        return view('whatsapp-template.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('whatsapp-template.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => ['required'],
            'lang'     => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $whatsapp_template            = new WhatsappTemplate();
        $whatsapp_template->template  = $request->template ?? null;
        $whatsapp_template->tamp_name = $request->tamp_name ?? null;
        $whatsapp_template->type = $request->type ?? null;
        $whatsapp_template->param = $request->param ?? null;
        $whatsapp_template->lang = $request->lang ?? null;
        $whatsapp_template->sort = $request->sort ?? null;
        $whatsapp_template->media = $request->media ?? null;
        $whatsapp_template->status = $request->status ?? null;
        $whatsapp_template->bulk_status = $request->bulk_status ?? null;
        $whatsapp_template->note = $request->note ?? null;
        $whatsapp_template->save();

        return redirect()->route('whatsapp-template.index')->with('success', 'Whatsapp Template created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WhatsappTemplate $whatsappTemplate)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $whatsappTemplate = WhatsappTemplate::findOrFail($id);
        return view('whatsapp-template.edit', compact('whatsappTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'template' => ['required'],
            'lang'     => ['required'],
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $whatsappTemplate = WhatsappTemplate::findOrFail($id);
        $whatsappTemplate->template  = $request->template ?? null;
        $whatsappTemplate->tamp_name = $request->tamp_name ?? null;
        $whatsappTemplate->type = $request->type ?? null;
        $whatsappTemplate->param = $request->param ?? null;
        $whatsappTemplate->lang = $request->lang ?? null;
        $whatsappTemplate->sort = $request->sort ?? null;
        $whatsappTemplate->media = $request->media ?? null;
        $whatsappTemplate->status = $request->status ?? null;
        $whatsappTemplate->bulk_status = $request->bulk_status ?? null;
        $whatsappTemplate->note = $request->note ?? null;
        $whatsappTemplate->save();

        return redirect()->route('whatsapp-template.index')->with('success', 'Whatsapp Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $whatsappTemplate = WhatsappTemplate::findOrFail($id);
        $whatsappTemplate->delete();
        return redirect()->route('whatsapp-template.index')->with('success', 'Whatsapp Template deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $whatsappTemplate = WhatsappTemplate::findOrFail($request->id);
        if (!$whatsappTemplate) {
            return response()->json(['success' => false, 'message' => 'Whatsapp Template not found']);
        }

        $whatsappTemplate->status = $request->status;
        $whatsappTemplate->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
    public function updateBulkStatus(Request $request)
    {
        $whatsappTemplate = WhatsappTemplate::findOrFail($request->id);
        if (!$whatsappTemplate) {
            return response()->json(['success' => false, 'message' => 'Whatsapp Template not found']);
        }

        $whatsappTemplate->bulk_status = $request->bulk_status;
        $whatsappTemplate->save();

        return response()->json(['success' => true, 'message' => 'Bulk status updated successfully']);
    }
}
