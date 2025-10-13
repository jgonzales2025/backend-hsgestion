<?php

namespace App\Modules\Ubigeo\Departments\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'coddep' => $this->resource->getCoddep(),
            'nomdep' => $this->resource->getNomdep(),
        ];
    }
}
