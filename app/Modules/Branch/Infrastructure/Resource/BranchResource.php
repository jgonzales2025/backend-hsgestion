<?php

namespace App\Modules\Branch\Infrastructure\Resource;

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
            'id' => $this->resource->getId(),
            'cia_id' => $this->resource->getCia_id(),
            'name' => $this->resource->getName(),
             'email' => $this->resource->getEmail(),
              'serie' => $this->resource->getSerie(),
            'address' => $this->resource->getAddress(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'phones'   => $this->resource->getPhones() ?? [],
        ];
    }
}
