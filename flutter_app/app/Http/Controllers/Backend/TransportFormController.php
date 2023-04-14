<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use Modules\Transportation\Models\Transportation;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransportFormController extends BackendBaseController
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
        $this->module_title = 'Form Builder';

        // module name
        $this->module_name = 'formbuilder';

        // directory path of the module
        $this->module_path = 'backend';

        // module icon
        $this->module_icon = 'fa fa-hammer';

        // module model name, path
        $this->module_model = "App\Models\TransportForm";

        $this->softDeleteAction = false;
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
        return view(
            "$module_path.$module_name.index",
            compact('module_title', 'module_name', 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function index_data()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';
        DB::statement(DB::raw('set @rownum=0'));
        $$module_name = $module_model::join('transportations','transport_forms.transportation_id','=', 'transportations.id')->select([DB::raw('@rownum := @rownum + 1 AS rownum'),'transport_forms.id','transport_forms.transportation_id','transport_forms.status','transport_forms.created_at','transportations.image as icon','transportations.transport_type as transport']);

        $data = $$module_name;
        return DataTables::of($$module_name)
                        ->editColumn('icon', function($data){
                            return $data->icon = "<img src='".$data->icon."' style='width:50px;height:50px' />";
                        })
                        ->editColumn('status', function($data){
                            $isActive = '';
                            $isInactive = '';
                            $isActiveRoute = '';
                            $isInactiveRoute = '';
                            if($data->status == 1){
                                $btn = 'btn-success';
                                $text = 'Active';
                                $isActive = 'active';
                                $isInactiveRoute = 'href="'.route('backend.'.$this->module_name.'.status',[$data->id,0]).'"';
                            }else{
                                $btn = 'btn-danger';
                                $text = 'Inactive';
                                $isInactive = 'active';
                                $isActiveRoute = 'href="'.route('backend.'.$this->module_name.'.status',[$data->id,1]).'"';
                            }
                            return $data->status = '<div class="btn-group">
                                <button class="btn btn-sm '.$btn.' dropdown-toggle" type="button"
                                    data-coreui-toggle="dropdown" aria-expanded="false">'.$text.'</button>
                                <ul class="dropdown-menu" style="">
                                    <li><a class="dropdown-item '.$isActive.'" '.$isActiveRoute.'>Active</a></li>
                                    <li><a class="dropdown-item '.$isInactive.'" '.$isInactiveRoute.'>Inactive</a></li>
                                </ul>
                            </div>';
                        })
                        ->filterColumn('status', function($query, $keyword) {
                            $search = $keyword;
                            $active = "active";
                            $inactive = "inactive";
                            $pattern = "/$search/i";
                            if(preg_match($pattern, $active)){
                                $search = 1;
                            }else if(preg_match($pattern, $inactive)){
                                $search = 0;
                            }else{
                                $search = $search;
                            }
                            $sql = "transport_forms.status like ?";
                            $query->whereRaw($sql, ["%{$search}%"]);
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->filterColumn('transport', function($query, $keyword) {
                            $query->whereHas('transport',function($sub)use($keyword){
                                $sub->where('transport_type','like',["%{$keyword}%"]);
                            });
                        })
                        ->filterColumn('icon', function($query, $keyword) {
                            $query->whereHas('transport',function($sub)use($keyword){
                                $sub->where('image','like',["%{$keyword}%"]);
                            });
                        })
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            return view('backend.includes.action_column', compact('module_name', 'data','softDeleteAction'));
                        })
                        ->rawColumns(['action','status','icon'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }
    
    public function formBuilder()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $transports = Transportation::withCount('form')->get();
        return view(
            "$module_path.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action','transports')
        );
    }

    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';
        $formEdit = $module_model::findOrFail($id);
        $transports = Transportation::withCount('form')->get();
        
        return view(
            "$module_path.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action','transports','formEdit')
        );
    }

    public function store(Request $request) 
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        
        $module_action = 'Store';
        $rules = [
            'transportation_id' => 'required|integer|unique:transport_forms,transportation_id',
        ];
        $rulemessage = [
            'transportation_id.unique' => 'This Transportation form already Exist',
        ];
        if($request->has('id')){
            $module_model::findOrFail($request->id)->update($request->except('_token','id'));
            $message = "Form Builder Updated Successfully";
            $rules['transportation_id'] = $rules['transportation_id'].",".$request->id;
            $request->validate($rules,$rulemessage);
        }else{
            $request->validate($rules,$rulemessage);
            $module_model::create($request->except('_token'));
            $message = "New ".Str::singular($module_title) ." Created Successfully";
        }

        flash("<i class='fas fa-check'></i> $message")->success()->important();
        return redirect("admin/$module_name");
    }
}
