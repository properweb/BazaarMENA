<?php

namespace Modules\Settings\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;

class ShippingUpdateOrCreateInfoRequest extends FormRequest
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
        return [
            'address' => 'required|string',
            'country' => 'required|integer',
            'street' => 'required|string|max:255',
            'suite' => 'nullable|string|max:255',
            'state' => 'required|integer',
            'city' => 'required|integer',
            'zip' => 'required|integer',
            'country_code' => 'nullable|integer',
            'phone' => 'nullable|integer|min:9',
            'delivery_time_for_locations' => 'nullable|string|max:255',
            'delivery_fees' => 'nullable|string|max:255',
            'pickup' => 'nullable|integer',
            'delivery' => 'nullable|string|max:255',
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
