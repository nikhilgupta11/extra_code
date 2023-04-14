<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Driver extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'is_email_verified',
        'gender',
        'mobile_number',
        'slug',
        'otp',
        'vehicle_number',
        'license_number',
        'insurance_document',
        'license_document',
        'vehicle_rc',
        'bank_account_number',
        'ifsc_code',
        'bank_account_name',
        'bank_name',
        'bank_branch_name',
        'bank_branch_address',
        'wallet_balance',
        'is_available',
        'latitude',
        'longitude',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
