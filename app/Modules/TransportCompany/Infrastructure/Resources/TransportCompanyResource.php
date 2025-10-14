<?php

namespace App\Modules\TransportCompany\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransportCompanyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'ruc' => $this->resource->getRuc(),
            'company_name' => $this->resource->getCompanyName(),
            'address' => $this->resource->getAddress(),
            'nro_reg_mtc' => $this->resource->getNroRegMtc(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
