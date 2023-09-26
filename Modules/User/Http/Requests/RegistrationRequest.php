<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;
use App\Rules\DisallowedEmailDomains;
use Illuminate\Http\JsonResponse;
class RegistrationRequest extends FormRequest
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
            'first_name' => 'required|regex:/^[a-zA-Z]+$/u|max:255',
            'last_name' => 'required|regex:/^[a-zA-Z ]+$/u|max:255',
            //'email' => 'required|email|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email',
            'email' => ['required', 'email', 'max:255', 'unique:users,email', new DisallowedEmailDomains],
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'business_name' => 'required|string|max:255',
            'industry' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'address' => 'required|string',
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'state_id' => 'required|numeric',
            'phone_number' => [
                'required',
                'numeric',
                'digits_between:9,15', // Adjust the minimum and maximum digits as needed
                'regex:/^\+?[0-9]*$/' // Allows digits and an optional plus sign
            ],
            'country_code' => 'required|numeric',
            'logo' => 'nullable|mimes:jpeg,jpg,png',
            'business_registration_document' => 'required|mimes:doc,docx,pdf',
            'role' => 'required|string'
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
