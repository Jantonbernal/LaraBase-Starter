<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name'      => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'email'     => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'password'  => 'nullable|string|min:6',
            'phone'     => 'nullable|string|max:20',
            'file'      => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max
            'roles'     => 'bail|array',
        ];
    }

    public function attributes()
    {
        return [
            'name'      => 'Nombre',
            'last_name' => 'Apellido',
            'email'     => 'Correo Electrónico',
            'password'  => 'Contraseña',
            'phone'     => 'Teléfono',
            'file'      => 'Fotografía',
            'roles'     => 'Roles',
        ];
    }
}
