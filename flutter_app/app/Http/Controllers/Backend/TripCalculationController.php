<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TripCalculationController extends Controller
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
        $this->module_title = 'Calculations';

        // module name
        $this->module_name = 'calculations';

        // directory path of the module
        $this->module_path = 'calculations';

        // module icon
        $this->module_icon = 'fa fa-percent';

        // module model name, path
        $this->module_model = "App\Models\TripCalculation";
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
        $title = $page_heading . ' ' . ucfirst($module_action);
        // $$module_name = $module_model::join('trips','trip_calculations.trip_id','=', 'trips.id')->join('users','trips.user_id','=', 'users.id')->select('trip_calculations.*','users.name')->get();
        // dd($$module_name->toArray());
        return view(
            'backend.' . $this->module_name . '.index',
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'page_heading', 'title')
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
        $$module_name = $module_model::join('trips','trip_calculations.trip_id','=', 'trips.id')->join('users','trips.user_id','=', 'users.id')->select([DB::raw('@rownum := @rownum + 1 AS rownum'), 'trip_calculations.*','users.name AS name']);

        $data = $$module_name;
        return DataTables::of($$module_name)
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('trip', function ($sub) use ($keyword) {
                    $sub->whereHas('user',function($sub2)use($keyword){
                        $sub2->where('name', 'like', ["%{$keyword}%"]);
                    });
                });
            })
            ->filterColumn('rownum', function ($query, $keyword) {
                $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;
                $softDeleteAction = $this->softDeleteAction;
                $edit = false;
                $delete = false;
                return view('backend.includes.action_column', compact('module_name', 'data', 'softDeleteAction', 'edit', 'delete'));
            })
            ->rawColumns(['action', 'name'])
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

        logUserAccess($module_title . ' ' . $module_action . ' | Id: ' . $$module_name_singular->id);

        return view(
            "backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular")
        );
    }
}
