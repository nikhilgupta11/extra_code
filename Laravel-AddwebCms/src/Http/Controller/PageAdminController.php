<?php

namespace AddWeb\CMS\Http\Controller;

use AddWeb\CMS\Models\ComponentModel;
use AddWeb\CMS\Models\PageModel;
use Illuminate\Http\Request;

class PageAdminController extends Controller
{
    public function index(Request $request)
    {
        $pages = (new PageModel())->all();
        return view(
            'add-web-view::addwebcms.backend.page.list',
            [
                'pages' => $pages
            ]
        );
    }

    public function create(Request $request)
    {
        $page = (new PageModel());
        $components = (new ComponentModel())->select('id','name')->get();
        return view(
            'add-web-view::addwebcms.backend.page.create-edit',
            [
                'data' => $page,
                'components' => $components
            ]
        );
    }

    public function edit($id, Request $request)
    {
        $pageModel = (new PageModel())->find($id);
        return view(
            'add-web-view::addwebcms.backend.page.create-edit',
            [
                'data' => $pageModel
            ]
        );
    }

    public function savePage(Request $request)
    {
        /*$request->validate([
            'componentName' => 'required',
            'componentSlug' => 'required',
            'componentCode' => 'required',
        ]);*/

        $pageModel = (new PageModel());
        if(!empty($request->pageId)){
            $pageModel = $pageModel->find($request->pageId);
        }
        $pageModel->{PageModel::NAME} = $request->pageName;
        $pageModel->{PageModel::SLUG} = $request->pageSlug;
        $pageModel->{PageModel::TITLE} = $request->pageTitle;
        $pageModel->{PageModel::HTML_CODE} = $request->pageCode;
        $pageModel->{PageModel::HTML_COMPONENT_DATA} = $request->pageCodeSetting;
        $pageModel->save();

        return redirect()->route('admin.page.list');
    }
}