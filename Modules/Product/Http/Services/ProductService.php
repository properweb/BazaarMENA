<?php

namespace Modules\Product\Http\Services;

use Modules\Product\Entities\Product;
use Illuminate\Support\Str;
use Modules\Product\Entities\ProductVideo;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\ProductAdditional;
use Modules\Product\Entities\ProductKey;
use Modules\Product\Entities\ProductPrice;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected Product $product;
    private $productAbsPath = "";
    private $productRelPath = "";

    public function __construct()
    {
        $this->productAbsPath = public_path('uploads/products');
        $this->productRelPath = asset('public') . '/uploads/products/';
    }

    /**
     * Fetch All products By logged brand
     *
     * @param $request
     * @return array
     */
    public function fetch($request): array
    {
        $userId = auth()->user()->id;
        $resultArray = [];
        $productsCounts = Product::selectRaw('status, count(*) as count')
            ->where('user_id', $userId)
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        $allProductsCount = $productsCounts->sum();
        $publishedProductsCount = $productsCounts->get(1, 0);
        $unpublishedProductsCount = $productsCounts->get(0, 0);
        $draftProductsCount = $productsCounts->get(2, 0);

        $query = Product::with('productPrices')
            ->withMinPrice()->where('user_id', $userId);
        if(!empty($request->status))
        {
            switch ($request->status) {
                case 1:
                    $query->where('status', 1);
                    break;
                case 0:
                    $query->where('status', 0);
                    break;
                case 2:
                    $query->where('status', 2);
                    break;
                default:
                    break;
            }
        }

        switch ($request->sort_key) {
            case 2:
                $query->orderBy('name_english', 'DESC');
                break;
            case 3:
                $query->orderBy('updated_at', 'DESC');
                $query->orderBy('id', 'DESC');
                break;
            default:
                $query->orderBy('name_english', 'ASC');
                break;
        }
        if ($request->search_key && !in_array($request->search_key, array('undefined', 'null'))) {
            $query->where('name_english', 'Like', '%' . $request->search_key . '%');
        }
        $products = $query->paginate(20);
        foreach ($products as $product) {

                $availability = $product->stock > 0 ? 'in stock' : 'out of stock';
                $image = ProductImage::where('product_id',$product->id)->first();

            $resultArray[] = array(
                'id' => $product->id,
                'product_key' => $product->product_key,
                'name' => $product->name_english,
                'status' => $product->status,
                'sku' => $product->sku,
                'featured_image' => $image->images,
                'stock' => $product->stock,
                'price' => number_format($product->productPrices[0]->sale_price,2),
                'availability' => $availability
            );
        }

        $data = array(
            "products" => $resultArray,
            "publishedCount" => $publishedProductsCount,
            "unpublishedCount" => $unpublishedProductsCount,
            "productCount" => $allProductsCount,
            "DraftCount" => $draftProductsCount
        );


        return ['res' => true, 'msg' => "", 'data' => $data];
    }
    /**
     * Create New Product By logged Brand
     *
     * @param $request
     * @return array
     */
    public function create($request): array
    {

        $variables = $this->variables($request);
        $product = new Product();
        $product->fill($variables);
        $product->save();
        $lastInsertId = $product->id;

        if (!empty($request->file('video_url'))) {
            foreach ($request->file('video_url') as $file) {
                $fileName = rand() . time() . '.' . $file->extension();
                $file->move($this->productAbsPath, $fileName);
                $video = new ProductVideo();
                $video->product_id = $lastInsertId;
                $video->video_url = $this->productRelPath . $fileName;
                $video->save();
            }
        }
        if (!empty($request->file('product_images'))) {
            foreach ($request->file('product_images') as $key => $file) {
                $fileName = rand() . time() . '.' . $file->extension();
                $file->move($this->productAbsPath, $fileName);
                $productImage = new ProductImage();
                $productImage->product_id = $lastInsertId;
                $productImage->images = $this->productRelPath . $fileName;
                $productImage->save();
            }
        }

        $keyDescription = $request->key_description;
        if (is_countable($keyDescription) && count($keyDescription) > 0) {
            foreach ($keyDescription as $feature) {
                $productFeature = new ProductKey();
                $productFeature->product_id = $lastInsertId;
                $productFeature->key_description = $feature;
                $productFeature->save();
            }
        }

        $prices= $request->prices;
        if (is_countable($prices) && count($prices) > 0) {
            foreach ($prices as $price) {
                $productPrice = new ProductPrice();
                $productPrice->product_id = $lastInsertId;
                $productPrice->min_order = $price['min_order'];
                $productPrice->unit_price = $price['unit_price'];
                $productPrice->sale_price = $price['sale_price'];
                $productPrice->save();
            }
        }

        $additional= $request->additional;
        if (is_countable($additional) && count($additional) > 0) {
            foreach ($additional as $add) {
                $productAdditional = new ProductAdditional();
                $productAdditional->product_id = $lastInsertId;
                $productAdditional->labels = $add['labels'];
                $productAdditional->values = $add['values'];
                $productAdditional->save();
            }
        }

        return [
            'res' => true,
            'msg' => 'Product created successfully',
            'data' => ''
        ];
    }

    /**
     * Product Details for respected product
     *
     * @param $request
     * @return array
     */

    public function details($request): array
    {
        $resultArray = [];

        $products = Product::where('id', $request->id)->with('productImages', 'productVideos', 'productPrices', 'ProductKeys', 'ProductAdditionals')->first();

        $productImages = $products->productImages;
        $productVideos = $products->productVideos;
        $productPrices = $products->productPrices;
        $ProductKeys = $products->ProductKeys;
        $productAdditions = $products->ProductAdditionals;
        if ($products) {

            $resultArray[] = array(
                'id' => $products->id,
                'name_english' => $products->name_english,
                'name_arabic' => $products->name_arabic,
                'category' => $products->category,
                'brand' => $products->brand,
                'stock' => $products->stock,
                'keyword_english' => $products->keyword_english,
                'keyword_arabic' => $products->keyword_arabic,
                'description_english' => strip_tags($products->description_english),
                'description_arabic' => $products->description_arabic,
                'barcode_type' => $products->barcode_type,
                'barcode' => $products->barcode,
                'sku' => $products->sku,
                'pack_size' => $products->pack_size,
                'pack_unit' => $products->pack_unit,
                'pack_avg' => $products->pack_avg,
                'pack_mode' => $products->pack_mode,
                'pack_carton' => $products->pack_carton,
                'carton_weight' => $products->carton_weight,
                'carton_weight_unit' => $products->carton_weight_unit,
                'carton_length' => $products->carton_length,
                'carton_length_unit' => $products->carton_length_unit,
                'carton_height' => $products->carton_height,
                'carton_height_unit' => $products->carton_height_unit,
                'carton_width' => $products->carton_width,
                'carton_width_unit' => $products->carton_width_unit,
                'price_unit' => $products->price_unit,
                'ready_ship' => $products->ready_ship,
                'availability' => $products->availability,
                'is_jordan' => $products->is_jordan,
                'storage_temp' => $products->storage_temp,
                'country_origin' => $products->country_origin,
                'warning' => $products->warning,
                'scent' => $products->scent,
                'gender' => $products->gender,
                'item_weight' => $products->item_weight,
                'item_height' => $products->item_height,
                'item_length' => $products->item_length,
                'item_width' => $products->item_width,
                'productImages' => $productImages,
                'productVideos' => $productVideos,
                'productPrices' => $productPrices,
                'ProductKeys' => $ProductKeys,
                'productAdditions' => $productAdditions,

            );
        }

        return ['res' => true, 'msg' => "", 'data' => $resultArray];
    }

    /**
     * Function of get variables from request
     *
     * @param $request
     * @param string $status
     * @return array
     */
    private function variables($request,$status=''): array
    {

        $userId = auth()->user()->id;
        $productKey = 'p_' . Str::lower(Str::random(10));
        $productSlug = Str::slug($request->name_english);
        $returnArray =array(
            'user_id' => $userId,
            'name_english' => $request->name_english,
            'name_arabic' => $request->name_arabic ?? '',
            'status' => 1,
            'slug' => $productSlug,
            'category' => $request->category,
            'brand' => $request->brand,
            'keyword_english' => $request->keyword_english ?? '',
            'keyword_arabic' => $request->keyword_arabic ?? '',
            'description_english' => $request->description_english ?? '',
            'description_arabic' => $request->description_arabic ?? '',
            'barcode_type' => $request->barcode_type ?? '',
            'barcode' => $request->barcode ?? '',
            'stock' => $request->stock ?? 0,
            'sku' => $request->sku ?? '',
            'pack_size' => $request->pack_size ?? '',
            'pack_unit' => $request->pack_unit ?? '',
            'pack_avg' => $request->pack_avg ?? '',
            'pack_mode' => $request->pack_mode ?? '',
            'pack_carton' => $request->pack_carton ?? '',
            'carton_weight' => $request->carton_weight ?? '',
            'carton_weight_unit' => $request->carton_weight_unit ?? '',
            'carton_length' => $request->carton_length ?? '',
            'carton_length_unit' => $request->carton_length_unit ?? '',
            'carton_height' => $request->carton_height ?? '',
            'carton_height_unit' => $request->carton_height_unit ?? '',
            'carton_width' => $request->carton_width ?? '',
            'carton_width_unit' => $request->carton_width_unit ?? '',
            'price_unit' => $request->price_unit ?? '',
            'ready_ship' => $request->ready_ship ?? '0',
            'availability' => $request->availability ?? '0',
            'is_jordan' => $request->is_jordan ?? '0',
            'storage_temp' => $request->storage_temp ?? '',
            'country_origin' => $request->country_origin ?? '0',
            'warning' => $request->warning ?? '',
            'scent' => $request->scent ?? '',
            'gender' => $request->gender ?? '',
            'item_weight' => $request->item_weight ?? '',
            'item_height' => $request->item_height ?? '',
            'item_length' => $request->item_length ?? '',
            'item_width' => $request->item_width ?? '',
        );
        if($status!='update' && $status==''){
            $returnArray['product_key'] = $productKey;
        }
        return $returnArray;
    }

    private function image64Upload($image): string
    {
        $image_64 = $image;
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image_64 = str_replace($replace, '', $image_64);
        $image_64 = str_replace(' ', '+', $image_64);
        $imageName = Str::random(10) . '.' . 'png';
        File::put($this->productAbsPath . "/" . $imageName, base64_decode($image_64));

        return $this->productRelPath . $imageName;
    }
}
