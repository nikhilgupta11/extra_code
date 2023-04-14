<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCalculation extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'transport_emission_total', 'accommodation_emission_total', 'total_co2_per_person', 'total_emission'];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }
}
