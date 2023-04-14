<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'ride_id',
        'review_by',
        'review_to',
        'rating',
        'description',
        'status',
    ];
}
