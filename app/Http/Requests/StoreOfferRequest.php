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
            'offer_category_id' => 'required|exists:offer_categories,id',
            'description'       => 'nullable|string|max:1000',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date|after:start_date',
            'terms'             => 'nullable|array',
            'terms.*'           => 'string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'contact_number' => [
                'required',
                'regex:/^[0-9+\-\s()]{8,15}$/',
            ],
            'order'             => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'discount.required'          => 'Discount is required.',
            'offer_category_id.required' => 'Please select a category.',
            'offer_category_id.exists'   => 'Selected category is invalid.',
            'start_date.required'        => 'Start date is required.',
            'end_date.required'          => 'End date is required.',
            'end_date.after'             => 'End date must be after start date.',
            'contact_number.required'    => 'Contact number is required.',
            'contact_number.regex'       => 'Please enter a valid contact number.',
            // Image validation messages
            'image.image' => 'Please upload a valid image.',
            'image.mimes' => 'Please upload a valid image in JPG, JPEG, PNG, or WEBP format.',
            'image.max'   => 'Image size must not exceed 2 MB.',
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
