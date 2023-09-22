<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;

    const ROLE_BRAND = 'brand';
    const ROLE_RETAILER = 'retailer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'business_name', 'industry', 'category_id', 'address', 'country_id', 'city_id', 'state_id', 'zip', 'phone_number', 'country_code', 'logo', 'letter_of_incorporation', 'vat_number', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
