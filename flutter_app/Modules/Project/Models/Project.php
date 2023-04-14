<?php

namespace Modules\Project\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'banner',
        'category_id',
        'category_name',
        'sku',
        'price',
        'sale_price',
        'stock',
        'threshold',
        'certification_start_txt',
        'certification_end_txt',
        'certification_start_number',
        'certification_end_number',
        'description',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Project\database\factories\ProjectFactory::new();
    }

    public function category()
    {
        return $this->belongsTo('Modules\Article\Entities\Category');
    }

    public function tags()
    {
        return $this->morphToMany('Modules\Tag\Entities\Tag', 'taggable');
    }
}
