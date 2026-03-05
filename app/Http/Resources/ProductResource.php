<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'unit' => $this->unit,
            // raw numeric value (for calculations)
            'price' => (float) $this->price,

            // formatted value (for UI display)
            'price_formatted' => '₱ ' . number_format($this->price, 2),
            'is_active' => $this->is_active,
            // REQUIRED for selects / forms
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'category' => $this->category?->name,

            'subcategory' => $this->subcategory?->name,

            'brand' => $this->brand ? [
                'name' => $this->brand->name,
                'image' => $this->brand->image
                    ? asset('storage/' . $this->brand->image)
                    : null,
            ] : null,
            'brand_image' => $this->brand ? [
                'name' => $this->brand->name,
                'image' => $this->brand->image
                    ? asset('storage/' . $this->brand->image)
                    : null,
            ] : null,

            'description' => $this->description,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->created_at
                ? $this->created_at->format('d M Y')
                : null,
            'updated_at_formatted' => $this->updated_at
                ? $this->updated_at->format('d M Y')
                : null,
            
        ];
    }
}
