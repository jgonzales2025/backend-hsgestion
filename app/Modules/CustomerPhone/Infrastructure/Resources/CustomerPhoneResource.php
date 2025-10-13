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
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
