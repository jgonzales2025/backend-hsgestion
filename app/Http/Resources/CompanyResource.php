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
            'id' => $this->resource->id,
            'ruc' => $this->resource->ruc,
            'company_name' => $this->resource->company_name,
            'address' => $this->resource->address,
            'ubigeo' => $this->resource->ubigeo,
            'start_date' => $this->resource->start_date,
            'status' => ($this->resource->status) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
