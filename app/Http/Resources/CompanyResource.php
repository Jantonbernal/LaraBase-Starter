<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            =>  $this->id,
            'business_name' =>  $this->business_name,
            'trade_name'    =>  $this->trade_name,
            'document'      =>  $this->document,
            'email'         =>  $this->email,
            'phone_number'  =>  $this->phone_number,
            'file_id'       =>  $this->file_id,
            'logo'          =>  new FileResource($this->whenLoaded('logo')),
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
