<?php

namespace App\Http\Requests\Sale;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'warehouse_id' => [
                'required',
                'exists:warehouses,id'
            ],

            'payment_amount' => [
                'required',
                'numeric',
                'min:0'
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'remarks' => [
                'nullable',
                'string'
            ],

            // Cart items
            'items' => [
                'required',
                'array',
                'min:1'
            ],

            // Each item product
            'items.*.product_id' => [
                'required',
                'exists:products,id'
            ],

            // Quantity
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],
        ];
    }
}