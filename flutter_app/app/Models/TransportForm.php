<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Transportation\Models\Transportation;

class TransportForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'transportation_id',
        'form_data',
        'extras',
        'status'
    ];

    public function transport()
    {
        return $this->belongsTo(Transportation::class,'transportation_id');
    }
}
