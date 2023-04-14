<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','card_holder','card_number','expiry','card_code'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
