<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\ProductImage;
use DB;

class Product extends Model
{
    protected $fillable = [
        'product_key',
        'status',
        'import_type',
        'user_id',
        'name_english',
        'name_arabic',
        'keyword_english',
        'keyword_arabic',
        'slug',
        'category',
        'brand',
        'description_english',
        'description_arabic',
        'barcode_type',
        'barcode',
        'sku',
        'pack_size',
        'pack_unit',
        'pack_avg',
        'pack_mode',
        'pack_carton',
        'stock',
        'carton_weight',
        'carton_weight_unit',
        'carton_length',
        'carton_length_unit',
        'carton_height',
        'carton_height_unit',
        'carton_width',
        'carton_width_unit',
        'price_unit',
        'ready_ship',
        'availability',
        'is_jordan',
        'storage_temp',
        'origin',
        'warning',
        'scent',
        'gender',
        'item_weight',
        'item_height',
        'item_length',
        'item_width'
    ];
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function productVideos()
    {
        return $this->hasMany(ProductVideo::class);
    }
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }
    public function ProductKeys()
    {
        return $this->hasMany(ProductKey::class);
    }
    public function ProductAdditionals()
    {
        return $this->hasMany(ProductAdditional::class);
    }
    public function scopeWithMinPrice($query)
    {
        return $query->with(['productPrices' => function ($query) {
            $query->select('product_id', DB::raw('min(sale_price) as sale_price'))
                ->groupBy('product_id');
        }]);
    }
}
