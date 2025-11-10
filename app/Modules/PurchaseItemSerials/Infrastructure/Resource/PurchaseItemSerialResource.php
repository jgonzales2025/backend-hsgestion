<?php 

namespace App\Modules\PurchaseItemSerials\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseItemSerialResource extends JsonResource{
    public function toArray(Request $request):array{

        return  [
            'id' => $this->resource->getId(),
            'purchase_guide_id' => $this->resource->getPurchaseGuideId(),
            'article_id' => $this->resource->getArticleId(),
            'serial' => $this->resource->getSerial(),
        
        ];
       
}
}