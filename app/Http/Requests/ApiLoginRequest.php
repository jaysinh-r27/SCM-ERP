<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApiLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login_input' => 'required|string',
            'password' => 'required|string|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'login_input.required' => 'Please enter your valid email, phone number or username.',
            'password.required' => 'Please enter your password.',
        ];
    }
}
