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
        $ubigeo = $this->resource->getUbigeo();

        return [
            'id' => $this->resource->getId(),
            'cia_id' => $this->resource->getCia_id(),
            'name' => $this->resource->getName(),
            'email' => $this->resource->getEmail(),
            'serie' => $this->resource->getSerie(),
            'start_date' => $this->resource->getStart_date(),
            'address' => $this->resource->getAddress(),
            'department_id' => substr($ubigeo, 0, 2),
            'province_id' => substr($ubigeo, 2, 2),
            'district_id' => substr($ubigeo, 4, 2),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            'phones'   => $this->resource->getPhones() ?? [],
        ];
    }
}
