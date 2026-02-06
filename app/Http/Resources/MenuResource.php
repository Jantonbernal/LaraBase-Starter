<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
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
            'menu'          => $this->menu,
            'hierarchy'     => $this->hierarchy,
            'parent'        => $this->parent,
            'permission_id' => $this->permission_id,
            'icon'          => $this->icon,
            'status'        => $this->status,
            'status_name'   => $this->status->label(),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'allChildrenMenus'  => MenuResource::collection($this->whenLoaded('allChildrenMenus'))
        ];
    }
}
