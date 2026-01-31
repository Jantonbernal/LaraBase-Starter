<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'slug'          => $this->slug,
            'name'          => $this->name,
            'status'        => $this->status,
            'status_name'   => $this->status->label(),
            'role'          => RoleResource::collection($this->whenLoaded('roles')),
            'created_at'    => $this->created_at,
        ];
    }
}
