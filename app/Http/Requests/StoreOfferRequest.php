<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'             => 'nullable|string|max:255',
            'discount'          => 'required|string|max:100',
            'offer_category_id' => 'nullable|exists:offer_categories,id',
            'description'       => 'nullable|string|max:1000',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'terms'             => 'nullable|array',
            'terms.*'           => 'string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'contact_number'    => 'nullable|string|max:20',
            'order'             => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'discount.required'    => 'Discount is required.',
            'start_date.required'  => 'Start date is required.',
            'end_date.required'    => 'End date is required.',
            'end_date.after'       => 'End date must be after start date.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}