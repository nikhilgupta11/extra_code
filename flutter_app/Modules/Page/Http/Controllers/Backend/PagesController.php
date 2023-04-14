<?php

namespace Modules\Page\Http\Controllers\Backend;

use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
class PagesController extends BackendBaseController
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Pages';

        // module name
        $this->module_name = 'pages';

        // directory path of the module
        $this->module_path = 'page::backend';

        // module icon
        $this->module_icon = 'fa fa-file';

        // module model name, path
        $this->module_model = "Modules\Page\Models\Page";

        $this->softDeleteAction = true;
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
        $$module_name = $module_model::select([DB::raw('@rownum := @rownum + 1 AS rownum'),'id','name', 'content', 'status','slug','page_type']);

        $data = $$module_name;

        return DataTables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            return view('backend.includes.action_column', compact('module_name','softDeleteAction','data'));
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
                            $sql = "status like ?";
                            $query->whereRaw($sql, ["%{$search}%"]);
                        })
                        ->filterColumn('rownum', function($query, $keyword) {
                            $query->whereRaw('@rownum  + 1 like ?', ["%{$keyword}%"]);
                        })
                        ->editColumn('page_type', function($data){
                            return $data->page_type = ucwords($data->page_type);
                        })
                        ->rawColumns(['status','action','page_type'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:pages'
        ]);
        $data = $request->except('_token');
        if($request->has('files')){
            foreach ($data['files'] as $key => $value) {
                if(trim($value) == null){
                    unset($data['files'][$key]);
                }
            }
            $data['imgs_videos'] = json_encode($data['files']);
        }
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';
        $$module_name_singular = $module_model::create($data);

        flash("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/$module_name");
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required|unique:pages,name,'.$id
        ]);

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $$module_name_singular = $module_model::findOrFail($id);
        $data = $request->except('_token');
        if($request->has('files')){
            foreach ($data['files'] as $key => $value) {
                if(trim($value) == null){
                    unset($data['files'][$key]);
                }
            }
            $data['imgs_videos'] = json_encode($data['files']);
        }else{
            $data['imgs_videos'] = json_encode([]);
        }
        $$module_name_singular->update($data);

        flash("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Updated Successfully")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("backend.$module_name.show", $$module_name_singular->id);
    }
}
