<?php

namespace Modules\Settings\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;


class Shipping extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'user_id', 'address', 'country', 'street', 'suite', 'state', 'city', 'zip', 'country_code', 'phone','delivery_time_for_locations','delivery_fees','pickup','delivery'
    ];
}
