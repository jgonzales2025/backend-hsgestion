<?php

namespace App\Modules\Withholding\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithholdingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'date' => $this->resource->date,
            'percentage' => $this->resource->percentage,
        ];
    }
}