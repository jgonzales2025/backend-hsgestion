<?php

namespace App\Modules\PercentageIGV\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PercentageIGVResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->getId(),
            'date' => $this->resource->getDate()->format('Y-m-d'),
            'percentage' => $this->resource->getPercentage(),
        ];
    }
}
