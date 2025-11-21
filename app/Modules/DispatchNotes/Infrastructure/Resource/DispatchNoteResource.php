<?php

namespace App\Modules\DispatchNotes\Infrastructure\Resource;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
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
            'destination_address_customer' => $this->resource->getDestinationAddressCustomer(),
            'transport' => [
                'id' => $this->resource->getTransport()?->getId(),
                'status' => $this->resource->getTransport()?->getStatus(),
                'name' => $this->resource->getTransport()?->getCompanyName()

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
                'name' => $this->resource->getConductor()?->getName()
            ],
            'license_plate' => $this->resource->getLicensePlate(),
            'total_weight' => $this->resource->getTotalWeight(),
            'transfer_type' => $this->resource->getTransferType(),
            'vehicle_type' => $this->resource->getVehicleType(),
            'reference_document_type' => [
                'id' => $this->resource->getReferenceDocumentType()?->getId(),
                'status' => ($this->resource->getReferenceDocumentType()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'description' => $this->resource->getReferenceDocumentType()?->getDescription(),
            ],
            'destination_branch_client_id' => (function () {
                $code = EloquentCustomerAddress::where('id', $this->resource->getdestination_branch_client())->first();

                if (!$code) {
                    return [];
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->address,
                    // 'name' => $code->address[0]['address'],
    
                ];
            })(),

            'customer' => (function () {
                $code = EloquentCustomer::where('id', $this->resource->getCustomerId())->first();

                if (!$code) {
                    return [];
                }

                return (object) [
                    'id' => $code->id,
                    'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
                    'name' => $code->name,

                ];
            })(),

            'supplier' => [
                'id' => $this->resource->getSupplier()?->getId(),
                'status' => $this->resource->getSupplier()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getSupplier()?->getName(), 
            ],
            'address_supplier' => [
                'id' => $this->resource->getAddressSupplier()?->getId(),
                'status' => $this->resource->getAddressSupplier()?->getStatus() == 1 ? 'Activo' : 'Inactivo',
                'name' => $this->resource->getAddressSupplier()?->getName(),
            ],
            'created_at' => $this->resource->getCreatedFecha(),

        ];
    }
}