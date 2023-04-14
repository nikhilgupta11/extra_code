<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class EnquiryController extends Controller
{
    public $softDeleteAction = false;
    public $module_title;
    public $module_name;
    public $module_path;
    public $module_icon;
    public $module_model;
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Enquiries';

        // module name
        $this->module_name = 'enquiries';

        // directory path of the module
        $this->module_path = 'enquiries';

        // module icon
        $this->module_icon = 'c-icon cil-people';

        // module model name, path
        $this->module_model = "App\Models\Enquiry";
    }
    
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'List';

        $page_heading = ucfirst($module_title);
        $title = $page_heading.' '.ucfirst($module_action);

        return view(
            'backend.'.$this->module_name.'.index',
            compact('module_title','module_name','module_path', 'module_icon', 'module_action', 'module_name_singular', 'page_heading', 'title')
        );
    }

    public function index_list()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';
        DB::statement(DB::raw('set @rownum=0'));
        $$module_name = $module_model::select([DB::raw('@rownum := @rownum + 1 AS rownum'),'id','name','email','phone','description']);

        $data = $$module_name;
        return DataTables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            return view('backend.includes.action_column', compact('module_name', 'data','softDeleteAction'));
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->editColumn('description', function ($data) {
                            return $data->description = Str::of($data->description)->limit(20);
                        })
                        ->rawColumns(['action',])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';
        $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular->delete();

        flash('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Deleted Successfully!')->success()->important();

        return redirect("admin/$module_name");
    }

    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        $$module_name_singular = $module_model::findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            "backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular")
        );
    }
}
