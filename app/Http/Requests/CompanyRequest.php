<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
        // si se llama a update, la ruta incluye {company} en la petición
        $companyId = $this->route('company')?->id;

        return [
            'business_name' => 'bail|required|string|max:50|unique:companies,business_name,' . $companyId,
            'trade_name'    => 'bail|required|string|max:50|unique:companies,trade_name,' . $companyId,
            'document'      => 'bail|required|string|max:11|unique:companies,document,' . $companyId,
            'email'         => 'bail|required|email|max:20|unique:companies,email,' . $companyId,
            'phone_number'  => 'bail|required|string|max:20|unique:companies,phone_number,' . $companyId,
            'file'          => 'nullable|file|mimes:jpg,jpeg,png,gif|max:2048', // 2MB max
        ];
    }

    public function attributes(): array
    {
        return [
            'business_name' => 'Razón Social',
            'trade_name'    => 'Nombre Comercial',
            'document'      => 'Documento',
            'email'         => 'E-mail',
            'phone_number'  => 'Teléfono',
            'file'          => 'Fotografía',
        ];
    }
}
