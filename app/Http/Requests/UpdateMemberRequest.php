<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $memberId = $this->route('member');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,' . $memberId,
            'phone' => 'nullable|string|max:20',
             'address' => 'nullable|string|max:500',
            'company' => 'nullable|string|max:255',
            'chapter' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'expire_date' => 'nullable|date|after:joining_date',
            'designation' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
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
