<?php

namespace App\Modules\VisibleArticles\Infrastructure\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class VisibleArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'company_id' => $this->resource->getCompany_id(),
            'branch_id' => $this->resource->getBranch_id(),
            'article_id' => $this->resource->getArticle_id(),
            'user_id' => $this->resource->getUser_id(),
            'status' => $this->resource->getStatus()
        ];
    }
}