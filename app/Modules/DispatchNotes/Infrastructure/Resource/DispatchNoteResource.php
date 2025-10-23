<?php

namespace App\Modules\DispatchNotes\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DispatchNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->resource->getId(),

            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'status' => ($this->resource->getCompany()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
             'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'status' => ($this->resource->getBranch()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'emission_reason' => [
                'id' => $this->resource->getEmissionReason()->getId(),
                'status' => ($this->resource->getEmissionReason()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'destination_branch'=> [
                'id'=>$this->resource->getDestinationBranch()->getId(),
                'status' => $this->resource->getDestinationBranch()->getStatus()
            ],
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelativo(),
            'date' => $this->resource->getDate(),
            'description' => $this->resource->getDescription(),
            'destination_address_customer' => $this->resource->getDestinationAddressCustomer(),
            'transport' => [
                'id'=>$this->resource->getTransport()->getId(),
                'status'=>$this->resource->getTransport()->getStatus(),
            ],
            'observations' => $this->resource->getObservations(),
            'num_orden_compra' => $this->resource->getNumOrdenCompra(),
            'doc_referencia' => $this->resource->getDocReferencia(),
            'num_referencia' => $this->resource->getNumReferencia(),
            'serie_referencia' => $this->resource->getSerieReferencia(),
            'date_referencia' => $this->resource->getDateReferencia(),
            'status' => $this->resource->isStatus(),
            'conductor'=> [
                'id' =>$this->resource->getConductor()->getId(),
                'status'=>$this->resource->getConductor()->getStatus()
            ],
            'license_plate' => $this->resource->getLicensePlate(),
            'total_weight' => $this->resource->getTotalWeight(),
            'transfer_type' => $this->resource->getTransferType(),
            'vehicle_type' => $this->resource->getVehicleType(),
        ];
    }
}
