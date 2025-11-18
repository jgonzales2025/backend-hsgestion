<?php

namespace App\Modules\ShoppingIncomeGuide\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShoppingIncomeGuideResource extends JsonResource{
    public function toArray(Request $request): array{
        return [
            'purchase_id' => $this->resource->getPurchaseId(),
             'entry_guide_id' => $this->getEntryGuideId(),
        ];
    
}

}