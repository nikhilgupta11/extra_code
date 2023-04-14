<?php

namespace Modules\Accommodation\Models;

use App\Models\BaseModel;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends BaseModel
{
    use HasFactory;

    protected $table = 'accommodations';

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Accommodation\database\factories\AccommodationFactory::new();
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class,'trip_id');
    }
}
