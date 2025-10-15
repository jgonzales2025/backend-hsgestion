<?php

namespace App\Modules\CurrencyType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentTypeResource extends JsonResource{
        public function toArray($request):array{
            return [
                'id'=>$this->resource->getId(),
                 'name'=>($this->resource->getName()),
                'reference'=>($this->resource->getName())=="SOLES" ? "S/" : "$",
                'status'=>$this->resource->getStatus()
            ];
        }
}