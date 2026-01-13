<?php

namespace App\Modules\ArticleType\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleTypeResource extends JsonResource
{
  public function toArray($request)
  {
    return [
      'id' => $this->resource->getId(),
      'name' => $this->resource->getName(),
    ];
  }   
}