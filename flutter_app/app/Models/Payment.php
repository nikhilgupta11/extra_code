<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Project\Models\Project;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'user_id',
        'project_id',
        'order_id',
        'payment_id',
        'first_name',
        'last_name',
        'country',
        'state',
        'city',
        'street',
        'zip',
        'card_holder',
        'card_number',
        'expiry',
        'card_code',
        'discount',
        'amount',
        'payment_status',
        'certificate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
