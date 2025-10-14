<?php

namespace App\Modules\CustomerEmail\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerEmailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'email' => $this->resource->getEmail(),
            'status' => ($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo',
        ];
    }
}
