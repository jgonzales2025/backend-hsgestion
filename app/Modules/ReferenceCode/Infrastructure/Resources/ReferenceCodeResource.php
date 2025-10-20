<?php 
namespace App\Modules\ReferenceCode\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferenceCodeResource extends JsonResource{
      public function toArray($request): array{
        return [
             'id' => $this->resource->getId(),
            'ref_code' => $this->resource->getRefCode(),
            'article_id' => $this->resource->getArticleId(),
            'dateAt' => $this->resource->getDateAt(),
            'status' => ($this->resource->getStatus()) == true ? "Activo" :"Inactivo",
            // "creacion"=>$this->resource->getDateAt()
        ];
    }
}