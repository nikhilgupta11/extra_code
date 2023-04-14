<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Str;
use App\Models\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
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
        $this->module_title = 'Donations';

        // module name
        $this->module_name = 'donations';

        // directory path of the module
        $this->module_path = 'donations';

        // module icon
        $this->module_icon = 'fa fa-hand-holding-dollar';

        // module model name, path
        $this->module_model = "App\Models\Payment";
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
        $$module_name = $module_model::join('projects', 'payments.project_id', '=', 'projects.id')->join('users', 'payments.user_id', '=', 'users.id')->select([DB::raw('@rownum := @rownum + 1 AS rownum'), 'payments.*', 'projects.name as project' ,'users.name', 'users.email']);
        $data = $$module_name;
        return DataTables::of($$module_name)
            ->editColumn('project', function($data){
                return $data->project = "<span title='".$data->project."'>".Str::limit($data->project,30)."</span>";
            })
            ->filterColumn('project', function ($query, $keyword) {
                $query->whereHas('project', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('user', function ($sub) use ($keyword) {
                    $sub->where('name', 'like', "%$keyword%");
                });
            })
            ->filterColumn('email', function ($query, $keyword) {
                $query->whereHas('user', function ($sub) use ($keyword) {
                    $sub->where('email', 'like', "%$keyword%");
                });
            })
            ->filterColumn('rownum', function ($query, $keyword) {
                $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($data) {
                $module_name = $this->module_name;
                $softDeleteAction = $this->softDeleteAction;
                return view('backend.includes.action_column', compact('module_name', 'data', 'softDeleteAction'));
            })
            ->rawColumns(['action','project'])
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

        return view(
            "backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "$module_name_singular")
        );
    }
}
