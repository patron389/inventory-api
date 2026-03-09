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
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'brand_name' => $this->product->brand->name,
            'brand_image' => $this->product->brand && $this->product->brand->image
                ? asset('storage/' . $this->product->brand->image)
                : null,
            'category' => $this->product->category->name,
            'quantity' => $this->quantity,
        ];
    }
}
