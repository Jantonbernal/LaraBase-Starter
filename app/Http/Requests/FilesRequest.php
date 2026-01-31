<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilesRequest extends FormRequest
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
            'files'   => 'required|array', // Asegura que 'files' es un array
            'files.*' => 'file|max:20480|mimes:jpg,jpeg,png,webp,pdf',  // files.* para validar cada archivo en el array
            'path'    => 'nullable|regex:/^[a-zA-Z0-9-_\/]+$/', // Validar regex para path opcional
        ];
    }
}
