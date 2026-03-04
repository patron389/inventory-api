<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
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
            'name' => $this->name,
            'is_active' => $this->is_active,

            // Return full accessible URL
            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,
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
