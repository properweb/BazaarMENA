<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $fillable = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
