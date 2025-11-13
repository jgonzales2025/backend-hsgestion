<?php 

namespace App\Modules\EntryItemSerials\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntryItemSerialResource extends JsonResource{
    public function toArray(Request $request):array{

        return  [
            'id' => $this->resource->getId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
            'article_id' => $this->resource->getArticleId(),
            'serial' => match($this->resource->getSerial()) {
                0 => 'Vendido',
                2 => 'En tránsito',
                default => 'Disponible',
            },
        ];
       
    }
}