<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class,'promocode_user','promocode_id','user_id');
    }
}
