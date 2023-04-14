<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerify extends Model
{
    use HasFactory;

  

    public $table = "user_verifies";

  

    /**

     * Write code on Method

     *

     * @return response()

     */

    protected $fillable = [

        'user_id',

        'token',

    ];

  

    /**

     * Write code on Method

     *

     * @return response()

     */

    public function user()

    {

        return $this->belongsTo(User::class);

    }
    public function rider()

    {

        return $this->belongsTo(Rider::class);

    }
    public function driver()

    {

        return $this->belongsTo(Driver::class);

    }
}