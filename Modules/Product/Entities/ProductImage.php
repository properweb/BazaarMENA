<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Product;

class ProductImage extends Model
{
    protected $fillable = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
