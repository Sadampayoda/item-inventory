<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateWarehouseRequest extends FormRequest
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
        $id = $this->route('warehouse')->id;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('warehouses', 'code')->ignore($id),
            ],
            'name' => 'required|string|max:100',
            'descrption' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode warehouse wajib diisi',
            'code.unique' => 'Kode warehouse sudah digunakan',
            'name.required' => 'Nama warehouse wajib diisi',
        ];
    }
}
