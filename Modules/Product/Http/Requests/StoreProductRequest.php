<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        $priceOptionRules = 'required|regex:/^\d{0,9}(\.\d{0,2})?$/';
        $stockOptionRules = 'required_if:availability,1|integer';
        return [
            'category' => 'required|integer',
            'name_english' => 'required|string|min:15',
            'name_arabic' => 'nullable|string|min:15',
            'brand' => 'required|string|max:50',
            'keyword_english' => 'required|string|max:50',
            'keyword_arabic' => 'nullable|string|max:50',
            'product_images' => 'required|array|min:1',
            'video_url' => 'nullable|array',
            'key_description' => 'nullable|array',
            'description_english' => 'nullable|string',
            'description_arabic' => 'nullable|string',
            'barcode_type' => 'required|string',
            'barcode' => 'required|string|digits:12|unique:products,barcode',
            'sku' => 'required|string|unique:products,sku',
            'pack_size' => 'required|string',
            'pack_unit' => 'required|string',
            'pack_avg' => 'required|string',
            'pack_mode' => 'required|string',
            'pack_carton' => 'required|string',
            'carton_weight' => 'nullable|string',
            'carton_weight_unit' => 'nullable|string',
            'carton_length' => 'nullable|string',
            'carton_length_unit' => 'nullable|string',
            'carton_height' => 'nullable|string',
            'carton_height_unit' => 'nullable|string',
            'stock' => $stockOptionRules,
            'carton_width' => 'nullable|string',
            'carton_width_unit' => 'nullable|string',
            'price_unit' => 'required|string',
            'prices.*' => 'required|array|min:1',
            'prices.*.min_order' => 'required|integer',
            'prices.*.unit_price' => $priceOptionRules,
            'prices.*.sale_price' => $priceOptionRules,
            'ready_ship' => 'required|integer',
            'availability' => 'required|integer',
            'is_jordan' => 'required|integer',
            'country_origin' => 'required|integer',
            'warning' => 'nullable|string',
            'scent' => 'nullable|string',
            'gender' => 'required|string',
            'item_weight' => 'nullable|string',
            'item_height' => 'nullable|string',
            'item_length' => 'nullable|string',
            'item_width' => 'nullable|string',
            'additional.*' => 'nullable|array',
            'additional.*.labels' => 'nullable|string',
            'additional.*.values' => 'nullable|string',
        ];
    }

    /**
     * Create a json response on validation errors.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    public function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException(response()->json([
            'res' => false,
            'msg' => $validator->errors()->first(),
            'data' => ""
        ]));

    }
}
