<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Accommodation\Models\Accommodation;
use Modules\Project\Models\Project;
use Modules\Transportation\Models\Transportation;

class Trip extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'from',
        'to',
        'round_trip',
        'start_date',
        'trip_days',
        'peoples',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault([
            'name' => 'No User',
            'first_name' => 'No',
            'last_name' => 'User',
        ]);
    }

    public function transports()
    {
        return $this->belongsToMany(Transportation::class, 'trip_transportation');
    }

    public function transportsPivot()
    {
        return $this->transports()->withPivot('total_co2');
    }

    public function accommodation()
    {
        return $this->hasOne(Accommodation::class, 'trip_id')->withDefault([
            'country' => null,
            'name'    => $this->user->name ?? '',
            'number_overnights' => 0,
            'number_rooms' => 0,
            'hotel_stars' => 0,
        ]);
    }

    public function calculation()
    {
        return $this->hasOne(TripCalculation::class, 'trip_id')->withDefault([
            'transport_emission_total' => 0.00,
            'accommodation_emission_total' => 0.00,
            'total_emission_per_person' => 0.00,
            'total_emission' => 0.00,
        ]);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class,'trip_id');
    }

    public function frontPayment()
    {
        return $this->hasOne(Payment::class,'trip_id')->select('id','trip_id','project_id','certificate','amount','payment_status');
    }
}
