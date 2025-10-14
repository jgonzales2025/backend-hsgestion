<?php

namespace App\Modules\CurrencyType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrentTypeResource extends JsonResource{
        public function toArray($request):array{
            return [
                'id'=>$this->resource->getId(),
                'name'=>$this->resource->getName(),
                'status'=>$this->resource->getStatus()
            ];
        }
}