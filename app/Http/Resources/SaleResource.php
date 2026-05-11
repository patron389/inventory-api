<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'invoice_no' => $this->invoice_no,

            // 'warehouse' => [
            //     'id' => $this->warehouse?->id,
            //     'name' => $this->warehouse?->name,
            // ],
            'warehouse' => $this->warehouse?->name,
     

            // 'cashier' => [
            //     'id' => $this->user?->id,
            //     'name' => $this->user?->first_name . ' ' . $this->user?->last_name,
            // ],
            'cashier' => $this->user?->first_name . ' ' . $this->user?->last_name,

            'subtotal' => $this->subtotal,

            'discount' => $this->discount,

            'tax' => $this->tax,

            'total_amount' => $this->total_amount,

            'payment_amount' => $this->payment_amount,

            'change_amount' => $this->change_amount,

            'status' => $this->status,

            'remarks' => $this->remarks,

            'items' => SaleItemResource::collection($this->whenLoaded('items')),

            'created_at' => $this->created_at,

            'created_at_formatted' => $this->created_at
                ? $this->created_at->format('d M Y h:i A')
                : null,
        ];
    }
}