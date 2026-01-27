<?php

namespace App\Modules\DispatchNotes\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class TransferOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->getId(),
            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'ruc' => $this->resource->getCompany()->getRuc(),
                'company_name' => $this->resource->getCompany()->getCompanyName(),
            ],
            'origin_branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'name' => $this->resource->getBranch()->getName(),
            ],
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelative(),
            'emission_reason' => [
                'id' => $this->resource->getEmissionReason()->getId(),
                'description' => $this->resource->getEmissionReason()->getDescription(),
            ],
            'destination_branch' => [
                'id' => $this->resource->getDestinationBranch()->getId(),
                'name' => $this->resource->getDestinationBranch()->getName(),
            ],
            'observations' => $this->resource->getObservations(),
            'status' => $this->resource->getStatus() == 0 ? 'Inactivo' : 'Activo',
            'stage' => $this->resource->getStage() == 0 ? 'En traslado' : ($this->resource->getStage() == 2 ? 'Anulado' : 'Entregado'),
            'transfer_date' => $this->resource->getTransferDate(),
            'arrival_date' => $this->resource->getArrivalDate()
        ];
    }
}