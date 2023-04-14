<?php

namespace AddWeb\CMS\Http\Controller;

use AddWeb\CMS\Models\PageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class PageController extends Controller
{
    public function index($pageSlug)
    {
        $pageData = $this->getPageData($pageSlug);

        $html_code = strtr(
            $pageData->html_code,
            array(
                "\r\n" => "",
                "\r" => "",
                "\n" => "",
                "\t" => " ",
            )
        );

        $html = Blade::render(
            $html_code,
            ['data'=> $pageData->html_component_data]
        );

        return view(
            'add-web-view::addwebcms.frontend.page',
            [
                'html' => $html
            ]
        );
        //return Blade::render('<h1>{{$author}}</h1><div><x-add-web-cms componentSlug="component1" :data="$data" class="xxx" /></div><div><x-add-web-cms componentSlug="component1" :data="$data" /></div>',['data' => '[{"author" : "bhavin"}]', 'author' => 'BHAVIN' ], true);
        //return Blade::render(' <h1>{{$author}}</h1><div><x-add-web-cms componentSlug="component1" /></div><div><x-add-web-cms componentSlug="component1" /></div>',['data' => '[{"author" : "bhavin"}]', 'author' => 'BHAVIN' ], true);
    }

    private function getPageData($pageSlug)
    {
        return (new PageModel())->where('slug',$pageSlug)->first();
    }
}