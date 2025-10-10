<?php
namespace App\Modules\RecordType\Infrastructure\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class RecordTypeResource extends JsonResource{
    public function toArray($request): array{
        return [
            'id' => $this->resource->getId(),
            'name'=>$this->resource->getName(),
            'abbreviation'=>$this->resource->getAbbreviation(),
            'status'=>$this->resource->getStatus()
        ];
    }
}