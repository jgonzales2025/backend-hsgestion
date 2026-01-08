<?php

namespace App\Modules\DispatchNotes\Infrastructure\Resource;

use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExcelNoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->resource->getId(),

            'company' => [
                'id' => $this->resource->getCompany()->getId(),
                'status' => ($this->resource->getCompany()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'ruc' => $this->resource->getCompany()->getRuc(),
                'name' => $this->resource->getCompany()->getCompanyName(),
            ],
            'branch' => [
                'id' => $this->resource->getBranch()->getId(),
                'status' => ($this->resource->getBranch()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'direccion' => $this->resource->getBranch()->getAddress(),
            ],
            'emission_reason' => [
                'id' => $this->resource->getEmissionReason()->getId(),
                'status' => ($this->resource->getEmissionReason()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getEmissionReason()->getDescription()
            ],
            'destination_branch' => [
                'id' => $this->resource->getDestinationBranch()?->getId(),
                'status' => $this->resource->getDestinationBranch()?->getStatus(),
                'name' => $this->resource->getDestinationBranch()?->getName()
            ],
            'serie' => $this->resource->getSerie(),
            'correlativo' => $this->resource->getCorrelativo(),
            'description' => $this->resource->getDescription(),
            'transport' => [
                'id' => $this->resource->getTransport()->getId(),
                'status' => $this->resource->getTransport()->getStatus(),
                'name' => $this->resource->getTransport()?->getCompanyName(),
                'ruc' => $this->resource->getTransport()?->getRuc(),
                'company_name' => $this->resource->getTransport()?->getCompanyName()

            ],
            'observations' => $this->resource->getObservations(),
            'num_orden_compra' => $this->resource->getNumOrdenCompra(),
            'doc_referencia' => $this->resource->getDocReferencia(),
            'num_referencia' => $this->resource->getNumReferencia(),
            'date_referencia' => $this->resource->getDateReferencia(),
            'status' => $this->resource->getStatus() == "true" ? "Activo" : "Inactivo",
            'conductor' => [
                'id' => $this->resource->getConductor()?->getId(),
                'status' => $this->resource->getConductor()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getConductor()?->getName(),
                'pat_surname' => $this->resource->getConductor()?->getPatSurname(),
                'mat_surname' => $this->resource->getConductor()?->getMatSurname(),
                'license' => $this->resource->getConductor()?->getLicense()
            ],
            'license_plate' => $this->resource->getLicensePlate(),
            'total_weight' => $this->resource->getTotalWeight(),
            'transfer_type' => $this->resource->getTransferType(),
            'vehicle_type' => $this->resource->getVehicleType(),
            'document_type' => [
                'id' => $this->resource->getReferenceDocumentType()?->getId(),
                'status' => ($this->resource->getReferenceDocumentType()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getReferenceDocumentType()?->getDescription(),
            ],
            'destination_branch_client_id' => (function () {
                $code = \App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress::where('id', $this->resource->getdestination_branch_client())->first();

                if (!$code) {
                    return [];
                }

                return [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->address,
                ];
            })(),

            'date' => $this->resource->getCreatedFecha(),

            'customer' => (function () {
                $code = EloquentCustomer::where('id', $this->resource->getCustomerId())->first();

                if (!$code) {
                    return [];
                }

                return [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->company_name ?: trim($code->name . ' ' . $code->lastname . ' ' . $code->second_lastname),
                    'ruc' => $code->document_number ?? '',
                    'address' => data_get($code, 'address.0.address', ''),
                ];
            })(),
            'created_at' => $this->resource->getCreatedFecha(),

        ];
    }
}
