<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'username'   => $this->username,
            'email'      => $this->email,
            'phone_no'   => $this->phone_no,
            'is_active' => $this->is_active,
            // Return role names as array
            'roles' => $this->getRoleNames(),

            // Return permission names as array
            'permissions' => $this->getAllPermissions()
                                ->pluck('name')
                                ->values(),
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
