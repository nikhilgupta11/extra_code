<?php

namespace Modules\Project\Http\Controllers\Backend;

use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Article\Entities\Category;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends BackendBaseController
{
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Projects';

        // module name
        $this->module_name = 'projects';

        // directory path of the module
        $this->module_path = 'project::backend';

        // module icon
        $this->module_icon = 'fa fa-list';

        // module model name, path
        $this->module_model = "Modules\Project\Models\Project";

        $this->softDeleteAction = true;
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|min:3|max:100|unique:projects',
            'sku' => 'required|unique:projects,sku'
        ]);
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;

        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';
        $data = $request->except('tags_list','_token');
        $data['sku'] = strtoupper(str_replace(' ','',$data['sku']));
        $data['category_name'] = Category::find($request->category_id)->name ?? '';
        $$module_name_singular = $module_model::create($data);
        $$module_name_singular->tags()->attach($request->input('tags_list'));
        flash("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/$module_name");
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
        $$module_name = $module_model::select([DB::raw('@rownum := @rownum + 1 AS rownum'),'id', 'banner', 'name', 'status','stock','sku','price']);

        $data = $$module_name;

        return DataTables::of($$module_name)
                        ->addColumn('action', function ($data) {
                            $module_name = $this->module_name;
                            $softDeleteAction = $this->softDeleteAction;
                            return view('backend.includes.action_column', compact('module_name','softDeleteAction','data'));
                        })
                        ->editColumn('name', function($data){
                            return $data->name = "<strong title='".$data->name."'>".Str::limit($data->name,40)."</strong>";
                        })
                        ->editColumn('banner', function($data){
                            return $data->banner = '<img src="'.$data->banner.'" style="width:60px;height:60px;object-fit:cover" alt="">';
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
                        ->rawColumns(['name','banner','status', 'action'])
                        ->orderColumns(['id'], '-:column $1')
                        ->make(true);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|min:3|max:100|unique:projects,name,'.$id,
            'sku' => 'required|unique:projects,sku,'.$id
        ]);
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;

        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';
        $data = $request->except('tags_list','_token');
        $data['sku'] = strtoupper(str_replace(' ','',$data['sku']));
        $data['category_name'] = Category::find($request->category_id)->name ?? '';
        $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular->fill($data);
        $$module_name_singular->save();
        $$module_name_singular->tags()->attach($request->input('tags_list'));
        flash("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/$module_name");
    }
}
