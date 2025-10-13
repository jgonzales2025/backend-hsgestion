<?php
namespace App\Modules\PaymentType\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTypeResource extends JsonResource{
   public function toArray($request):array {
    return[
        'id'=>$this->resource->getId(),
        'name'=>$this->resource->getName(),
        'status'=>($this->resource->getStatus()) == 1 ? 'Activo' : 'Inactivo'
    ];
   }
}