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
            'price' => $this->price,
            'description' => $this->description,
            'is_active' => $this->is_active,

            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],

            'subcategory' => [
                'id' => $this->subcategory?->id,
                'name' => $this->subcategory?->name,
            ],

            'brand' => [
                'id' => $this->brand?->id,
                'name' => $this->brand?->name,
                'image' => $this->brand?->image
                    ? asset('storage/' . $this->brand->image)
                    : null,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
