<?php

namespace Modules\Banner\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductImage;
use DB;

class Banner extends Model
{
    protected $fillable = [
        'banner_image','banner_link','active'
    ];
}
