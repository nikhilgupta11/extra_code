<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Userprofile extends BaseModel
{
    use SoftDeletes;
    public $fillable = [
        'user_id',
        'gender',
        'url_website',
        'url_facebook',
        'url_twitter',
        'url_instagram',
        'url_linkedin',
        'date_of_birth',
        'address',
        'bio',
        'user_metadata',
        'last_ip',
        'login_count',
        'last_login',
        'email_verified_at',
        'status'
    ];
    protected $dates = [
        'date_of_birth',
        'last_login',
        'email_verified_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
