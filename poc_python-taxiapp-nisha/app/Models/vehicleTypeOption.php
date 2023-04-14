<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vehicleTypeOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'vehicle_type',
        'name',
        'per_km_price',
        'waiting_time',
        'waiting_charge',
        'capicity',
       
    ];
}
