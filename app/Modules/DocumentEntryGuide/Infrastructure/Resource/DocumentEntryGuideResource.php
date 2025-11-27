<?php

namespace App\Modules\DocumentEntryGuide\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentEntryGuideResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'entry_guide_id' => $this->resource->getEntryGuideId(),
            'guide_serie_supplier' => $this->resource->getGuideSerieSupplier(),
            'guide_correlative_supplier' => $this->resource->getGuideCorrelativeSupplier(),
            'invoice_serie_supplier' => $this->resource->getInvoiceSerieSupplier(),
            'invoice_correlative_supplier' => $this->resource->getInvoiceCorrelativeSupplier(),
        ];
    }    
}