<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageVehicleInfromation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'driver_id',
        'vehicle_type_id',
        'vehicle_type_option_id',
        'waiting_time',
        'waiting_charge',
        'vehicle_name',
        'vehicle_number',
      
     
    ];

}
