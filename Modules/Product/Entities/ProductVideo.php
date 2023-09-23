<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVideo extends Model
{
    protected $fillable = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
