<?php

namespace Modules\Accommodation\Http\Controllers\Backend;

use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class AccommodationsController extends BackendBaseController
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Accommodations';

        // module name
        $this->module_name = 'accommodations';

        // directory path of the module
        $this->module_path = 'accommodation::backend';

        // module icon
        $this->module_icon = 'fa fa-hotel';

        // module model name, path
        $this->module_model = "Modules\Accommodation\Models\Accommodation";

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
        DB::statement(DB::raw('set @rownum=0'));
        $$module_name = $module_model::select([DB::raw('@rownum := @rownum + 1 AS rownum'),'id','trip_id','name','country','number_overnights','number_rooms','hotel_stars']);

        $data = $$module_name;
        return DataTables::of($$module_name)
                        ->editColumn('trip_id', function ($data) {
                            return $data->trip_id = $data->trip_id;
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            $edit = false;
                            $delete = false;
                            return view('backend.includes.action_column', compact('module_name', 'data','softDeleteAction','edit','delete'));
                        })
                        ->rawColumns(['action','trip_id'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }
}
