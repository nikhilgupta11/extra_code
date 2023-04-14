<?php

namespace AddWeb\CMS\View\Component;

use AddWeb\CMS\Models\ComponentModel;
use AddWeb\CMS\Models\PageModel;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Component;

class AddWebViewComponentClass extends Component
{
    public $componentSlug;
    public $componentData;
    public function __construct($componentSlug,$data)
    {
        $this->componentSlug = $componentSlug;
        $this->componentData = $data;
    }

    public function render()
    {
        $componentHtml = $this->getDataBySlugForComponent($this->componentSlug);
        $componentDataArr = json_decode($this->componentData,true);
        $data = '';
        foreach ($componentDataArr as $componentData){
            $data .= Blade::render($componentHtml,$componentData[$this->componentSlug]);
        }
        return $data;
    }

    public function getDataBySlugForComponent($slug)
    {
        $component = (new ComponentModel())
            ->where(ComponentModel::SLUG,$slug)
            ->first();

        return $component->{ComponentModel::HTML_COMPONENT};
    }

    public function getDataBySlugFromPage($slug)
    {
        $page = (new PageModel())
            ->where(PageModel::SLUG,$slug)
            ->first();

        return $page->{PageModel::HTML_COMPONENT_DATA};
    }
}