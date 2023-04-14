<?php

namespace Modules\Transportation\Models;

use App\Models\BaseModel;
use App\Models\TransportForm;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportation extends BaseModel
{
    use HasFactory;
    protected $table = 'transportations';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Transportation\database\factories\TransportationFactory::new();
    }

    public function form()
    {
        return $this->hasOne(TransportForm::class);
    }
}
