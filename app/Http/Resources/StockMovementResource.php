<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'warehouse' => $this->warehouse->name,
            'product' => $this->product->name,
            'sku' => $this->product->sku,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reference' => $this->reference,
            'performed_by' => $this->user->username,
            'created_at' => $this->created_at,
        ];
    }
}
