<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'company' => 'nullable|string|max:255',
            'chapter' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'designation' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}