<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'regex:/^\+[1-9]\d{1,14}$/', 'required_without:email'],
            'email' => ['nullable', 'email', 'max:255', 'required_without:phone'],
            'subject' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:10000'],
            'files' => ['nullable', 'array', 'max:5'],
            'files.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,pdf,doc,docx'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone must be in E.164 format (e.g., +79001234567)',
            'phone.required_without' => 'Either phone or email is required',
            'email.required_without' => 'Either phone or email is required',
        ];
    }
}
