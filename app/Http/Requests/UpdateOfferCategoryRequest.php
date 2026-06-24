<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOfferCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offer_categories', 'name')->ignore(
                    $this->route('offer_category')
                ),
            ],
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'order' => 'required|integer|min:0',
            'status' => 'required|boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
