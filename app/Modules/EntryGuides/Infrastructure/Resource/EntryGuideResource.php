<?php

namespace App\Modules\EntryGuides\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;



class EntryGuideResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->getId(),
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelativo(),

            'date' => $this->resource->getDate(),
            'observations' => $this->resource->getObservations(),

            'reference_serie' => $this->resource->getReferenceSerie(),
            'reference_correlative' => $this->resource->getReferenceCorrelative(),

            'status' => $this->resource->getStatus() ? 'Activo' : 'Inactivo',
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'customer' => [
                'id' => $this->resource->getCustomer()->getId(),
                'name' => $this->resource->getCustomer()->getCompanyName() ??
                    trim($this->resource->getCustomer()->getName() . ' ' .
                        $this->resource->getCustomer()->getLastname() . ' ' .
                        $this->resource->getCustomer()->getSecondLastname()),
            ],
            'ingress_reason' => [
                'id' => $this->resource->getIngressReason()->getId(),
                'name' => $this->resource->getIngressReason()->getDescription(),
                'status' => ($this->resource->getIngressReason()->getStatus()) ? 'Activo' : 'Inactivo',
            ]
        ];
    }
}
