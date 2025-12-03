<?php

namespace App\Modules\PaymentMethodsSunat\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodSunatResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cod' => $this->resource->getCod(),
            'des' => $this->resource->getDes(),
        ];
    }
}
