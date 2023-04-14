<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'email',
        'phone',
        'description',
        'created_at',
        'updated_at'
    ];
}
