<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPassword extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                // 'email:rfc,dns'
            ],
            'code' => 'required',
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'confirmed:confirmPassword', // confirmación de contraseña personaliazado porque es normalmente password_confirmation 
                'regex:/[a-z]/', // at least one lowercase letter
                'regex:/[A-Z]/', // at least one uppercase letter
                'regex:/[0-9]/', // at least one digit
            ],
            'confirmPassword' => 'required|string|min:8',
        ];
    }
}
