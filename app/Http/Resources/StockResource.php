<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
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
            'quantity' => $this->quantity,
            'updated_at' => $this->updated_at,
        ];
    }
}
