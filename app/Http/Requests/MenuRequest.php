<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuRequest extends FormRequest
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
        // Captura el ID desde la ruta (ej: /api/menus/{menu})
        // Si es store, será null. Si es update, será el objeto/ID.
        $menuId = $this->route('menu')?->id;

        return [
            'menu' => [
                'bail',
                'required',
                'string',
                'max:191',
                // Ignora el ID actual si existe (Update), de lo contrario valida único (Store)
                Rule::unique('menus', 'menu')->ignore($menuId),
            ],
            'icon' => [
                'bail',
                'required',
                'string',
            ],
            'hierarchy' => [
                'bail',
                'sometimes',
                'integer',
            ],
            'permission_id' => [
                'bail',
                'nullable',           // Solo si viene en el request
                'exists:permissions,id' // Y debe existir en la BD
            ],
            'parent' => [
                'bail',
                'nullable',
                'integer',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'menu' => 'Menú',
            'icon' => 'Ícono',
            'permission_id' => 'Permiso',
            'parent' => 'Padre',
        ];
    }
}
