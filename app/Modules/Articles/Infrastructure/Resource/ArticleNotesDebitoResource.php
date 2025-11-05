<?php

namespace App\Modules\Articles\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleNotesDebitoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'description' => $this->resource->getFiltNameEsp()
        ];
    }
}