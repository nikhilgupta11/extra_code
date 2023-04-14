<?php

namespace Modules\Transportation\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TransportationsController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Transportations';

        // module name
        $this->module_name = 'transportations';

        // directory path of the module
        $this->module_path = 'transportation::backend';

        // module icon
        $this->module_icon = 'fa fa-truck-plane';

        // module model name, path
        $this->module_model = "Modules\Transportation\Models\Transportation";
        
        $this->softDeleteAction = false;
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

        $page_heading = label_case($module_title);
        $title = $page_heading.' '.label_case($module_action);
        DB::statement(DB::raw('set @rownum=0'));
        $$module_name = $module_model::select([DB::raw('@rownum := @rownum + 1 AS rownum'),'id', 'transport_type', 'image','status','created_at','updated_at']);

        $data = $$module_name;

        return DataTables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = true;
                            $delete = false;
                            return view('backend.includes.action_column', compact('module_name','softDeleteAction','delete','data'));
                        })
                        ->editColumn('transport_type', function($data){
                            return $data->transport_type = $data->transport_type;
                        })
                        ->editColumn('image', function($data){
                            return $data->image = '<img src="'.$data->image.'" style="width:60px;height:60px;object-fit:cover" alt="">';
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
                                $isInactiveRoute = 'href="'.route('backend.transportations.status',[$data->id,0]).'"';
                            }else{
                                $btn = 'btn-danger';
                                $text = 'Inactive';
                                $isInactive = 'active';
                                $isActiveRoute = 'href="'.route('backend.transportations.status',[$data->id,1]).'"';
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
                            $sql = "status like ?";
                            $query->whereRaw($sql, ["%{$search}%"]);
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->rawColumns(['transport_type', 'image', 'status','action'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'transport_type' => 'required|unique:transportations'
        ],
        [
            'unique' => 'The transport name has already been taken'
        ]);
        return parent::store($request);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'transport_type' => 'required|unique:transportations,transport_type,'.$id
        ],
        [
            'unique' => 'The transport name has already been taken'
        ]);
        return parent::update($request,$id);
    }
}
