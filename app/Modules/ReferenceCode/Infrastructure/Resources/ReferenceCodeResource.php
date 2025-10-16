<?php 
namespace App\Modules\ReferenceCode\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferenceCodeResource extends JsonResource{
      public function toArray($request): array{
        return [
             'id' => $this->resource->getId(),
            'refCode' => $this->resource->getRefCode(),
            'articleId' => $this->resource->getArticleId(),
            'dateAt' => $this->resource->getDateAt(),
            'status' => ($this->resource->getStatus()) == true ? "Activo" :"Inactivo",
            // "creacion"=>$this->resource->getDateAt()
        ];
    }
}