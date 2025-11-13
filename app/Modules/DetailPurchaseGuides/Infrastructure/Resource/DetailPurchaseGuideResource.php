<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailPurchaseGuideResource extends JsonResource{
    public function toArray(Request $request){
        return [
            'id'=>$this->resource->getId(),
            'article_id' => $this->resource->getArticleId()
        ];
    }
}