<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOutboundRequest extends FormRequest
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
            'warehouse_id'      => 'required|exists:warehouses,id',
            'transaction_date'  => 'required|date',
            'expired_date'      => 'nullable|date|after_or_equal:transaction_date',
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.required' => 'Warehouse wajib dipilih',
            'warehouse_id.exists'   => 'Warehouse tidak valid',
            'transaction_date.required' => 'Tanggal transaksi wajib diisi',
            'expired_date.after_or_equal' => 'Expired date harus >= tanggal transaksi',
        ];
    }
}
