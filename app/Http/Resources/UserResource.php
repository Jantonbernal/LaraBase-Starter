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
    public function toArray(Request $request): array
    {
        return [
            'code'          =>  $this->code,
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'last_name'     =>  $this->last_name,
            'full_name'     =>  $this->name . ' ' . $this->last_name,
            'email'         =>  $this->email,
            'phone'         =>  $this->phone,
            'permissions'   =>  PermissionResource::collection($this->whenLoaded('permissions')),
            'roles'         =>  RoleResource::collection($this->whenLoaded('roles')),
            'photo'         =>  new FileResource($this->whenLoaded('photo')),
            'status'        =>  $this->status,
            'status_name'   =>  $this->status->label(),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
