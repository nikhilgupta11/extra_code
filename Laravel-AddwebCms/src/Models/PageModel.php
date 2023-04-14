<?php

namespace AddWeb\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageModel extends Model
{
    use SoftDeletes;

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('addWebCms.table_prefix').'pages';
    }

    const ID = 'id';
    const NAME = 'name';
    const SLUG = 'slug';
    const TITLE = 'title';
    const HTML_CODE = 'html_code';
    const HTML_COMPONENT_DATA = 'html_component_data';
    protected $fillable = [
        self::NAME,
        self::SLUG,
        self::TITLE,
        self::HTML_CODE,
        self::HTML_COMPONENT_DATA
    ];
}