<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Support\Str;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TripController extends Controller
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
        $this->module_title = 'Trips';

        // module name
        $this->module_name = 'trips';

        // directory path of the module
        $this->module_path = 'trips';

        // module icon
        $this->module_icon = 'fa fa-road';

        // module model name, path
        $this->module_model = "App\Models\Trip";
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
        $$module_name = $module_model::join('users','trips.user_id','=', 'users.id')->select([DB::raw('@rownum := @rownum + 1 AS rownum'),'trips.*','users.name AS name']);
        $data = $$module_name;
        return DataTables::of($$module_name)
                        ->addColumn('transports', function ($data) {
                            return $data->transports = implode(',',$data->transports()->groupBy('transport_type')->pluck('transport_type')->toArray());
                        })
                        ->filterColumn('name', function($query, $keyword) {
                            $query->whereHas('user',function($sub)use($keyword){
                                $sub->where('name','like',"%$keyword%");
                            });
                        })
                        ->filterColumn('transports', function($query, $keyword) {
                            $query->whereHas('transports',function($sub)use($keyword){
                                $sub->where('transport_type','like',"%$keyword%");
                            });
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->editColumn('round_trip',function($data){
                            return $data->round_trip = ($data->round_trip == '0') ? 'No' : 'Yes';
                        })
                        ->filterColumn('round_trip', function($query, $keyword) {
                            if(strtolower($keyword) == "no"){
                                $keyword = 0;
                            }
                            if(strtolower($keyword) == "yes"){
                                $keyword = 1;
                            }
                            $query->where('round_trip','like',"%$keyword%");
                        })
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            return view('backend.includes.action_column', compact('module_name', 'data','softDeleteAction'));
                        })
                        ->rawColumns(['action','transports','round_trip'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
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
