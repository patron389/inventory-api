<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
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
            'from_warehouse_name' => $this->fromWarehouse?->name,
            'to_warehouse_name' => $this->toWarehouse?->name,
            'items_count' => $this->items->count(),
            'user_name' => $this->user 
                ? $this->user->first_name . ' ' . $this->user->last_name 
                : null,
            'status' => $this->status,
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
