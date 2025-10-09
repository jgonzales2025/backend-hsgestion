<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'cia_id' => $this->resource->cia_id,
            'name' => $this->resource->name,
            'address' => $this->resource->address,
            'status' => ($this->resource->status) == 1 ? 'Activo' : 'Inactivo'
        ];
    }
}
