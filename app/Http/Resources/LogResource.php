<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'route'     => $this->route,
            'method'    => $this->method,
            'message'   => $this->message,
            'payload'   => $this->payload,
            'user'      => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
