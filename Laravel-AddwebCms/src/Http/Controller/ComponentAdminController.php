<?php

namespace AddWeb\CMS\Http\Controller;

use AddWeb\CMS\Models\ComponentModel;
use Illuminate\Http\Request;

class ComponentAdminController extends Controller
{
    public function index(Request $request)
    {
        $components = (new ComponentModel())->all();
        return view(
            'add-web-view::addwebcms.backend.component.list',
            [
                'components' => $components
            ]
        );
    }

    public function create()
    {
        // todo minify html
        // todo validation more user friendly
        $componentModel = (new ComponentModel());
        return view(
            'add-web-view::addwebcms.backend.component.create-edit',
            [
                'data' => $componentModel
            ]
        );
    }

    public function edit($id)
    {
        $componentModel = (new ComponentModel())->find($id);
        if(request()->wantsJson()){
            return response()->json(compact('componentModel'));
        }
        return view(
            'add-web-view::addwebcms.backend.component.create-edit',
            [
                'data' => $componentModel
            ]
        );
    }

    public function saveComponent(Request $request)
    {
        $request->validate([
            'componentName' => 'required',
            'componentSlug' => 'required',
            'componentCode' => 'required',
        ]);

        $componentModel = (new ComponentModel);
        if(!empty($request->componentId)){
            $componentModel = $componentModel->find($request->componentId);
        }
        $componentModel->{ComponentModel::NAME} = $request->componentName;
        $componentModel->{ComponentModel::SLUG} = $request->componentSlug;
        $componentModel->{ComponentModel::HTML_COMPONENT} = $request->componentCode;
        $componentModel->save();

        return redirect()->route('admin.component.list');
    }
}