<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverAvailibility extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver_id',
        'day',
        'status',
        'start_time',
        'end_time',
    ];
}
