<?php

namespace AddWeb\CMS\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComponentModel extends Model
{
    use SoftDeletes;

    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('addWebCms.table_prefix').'components';
    }

    const ID = 'id';
    const NAME = 'name';
    const SLUG = 'slug';
    const HTML_COMPONENT = 'html_component';

    protected $fillable = [
        self::NAME,
        self::SLUG,
        self::HTML_COMPONENT,
    ];

    public function getHtmlComponentAttribute($value)
    {
        return $value;
        /*return strtr(
            $value,
            array(
                "\r\n" => "",
                "\r" => "",
                "\n" => "",
                "\t" => " ",
            )
        );*/
    }
}