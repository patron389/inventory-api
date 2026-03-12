<?php

namespace App\Http\Requests\Transfer;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransferRequest extends FormRequest
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
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id'   => 'required|exists:warehouses,id',

            // transfer items
            'items' => 'required|array|min:1',

            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ];
    }
}
