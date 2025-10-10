<?php

namespace App\Modules\CustomerPhone\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPhoneResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'phone' => $this->resource->getPhone(),
            'customer_id' => $this->resource->getCustomerId(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
