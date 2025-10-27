<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;

class EloquentDIspatchNoteRepository implements DispatchNotesRepositoryInterface
{
    public function findAll(): array
    {

        $dispatchNotes = EloquentDispatchNote::with([
            'company',
            'branch',
            'emission_reason',
            'transport',
            'conductor',
            'document_type'
        ])->get();

        return $dispatchNotes->map(function ($dispatch) {
            return new DispatchNote(
                id: $dispatch->id,
                company: $dispatch->company?->toDomain($dispatch->company),
                branch: $dispatch->branch?->toDomain($dispatch->branch),
                serie: $dispatch->serie,
                correlativo: $dispatch->correlativo,
                date: $dispatch->date,
                emission_reason: $dispatch->emission_reason->toDomain($dispatch->emission_reason),
                description: $dispatch->description,
                destination_branch: $dispatch->branch?->toDomain($dispatch->branch),
                destination_address_customer: $dispatch->destination_address_customer,
                transport: $dispatch->transport?->toDomain($dispatch->transport),
                observations: $dispatch->observations,
                num_orden_compra: $dispatch->num_orden_compra,
                doc_referencia: $dispatch->doc_referencia,
                num_referencia: $dispatch->num_referencia,
                date_referencia: $dispatch->date_referencia,
                status: $dispatch->status,
                conductor: $dispatch->conductor->toDomain($dispatch->conductor),
                license_plate: $dispatch->license_plate,
                total_weight: $dispatch->total_weight,
                transfer_type: $dispatch->transfer_type,
                vehicle_type: $dispatch->vehicle_type,
                document_type: $dispatch->document_type->toDomain($dispatch->document_type),
                destination_branch_client: $dispatch->destination_branch_client,
                customer_id: $dispatch->customer_id


            );
        })->toArray();
    }
    public function save(DispatchNote $dispatchNote): ?DispatchNote
    {
        $dispatchNote = EloquentDispatchNote::create([
            'cia_id' => $dispatchNote->getCompany() ? $dispatchNote->getCompany()->getId() : null,
            'branch_id' => $dispatchNote->getBranch() ? $dispatchNote->getBranch()->getId() : null,
            'serie' => $dispatchNote->getSerie(),
            'correlativo' => (
                ($dispatchNote->getDocumentType() ? strtoupper(substr($dispatchNote->getDocumentType()->getAbbreviation(), 0, 1)) : '') .
                '00' .
                ($dispatchNote->getBranch() ? strtoupper(substr($dispatchNote->getBranch()->getId(), 0, 1)) : '')
            ),

            'date' => $dispatchNote->getDate(),
            'emission_reason_id' => $dispatchNote->getEmissionReason() ? $dispatchNote->getEmissionReason()->getId() : null,
            'description' => $dispatchNote->getDescription(),
            'destination_branch_id' => $dispatchNote->getDestinationBranch() ? $dispatchNote->getDestinationBranch()->getId() : null,
            'destination_address_customer' => $dispatchNote->getDestinationAddressCustomer(),
            'transport_id' => $dispatchNote->getTransport() ? $dispatchNote->getTransport()->getId() : null,
            'observations' => $dispatchNote->getObservations(),
            'num_orden_compra' => $dispatchNote->getNumOrdenCompra(),
            'doc_referencia' => $dispatchNote->getDocReferencia(),
            'num_referencia' => $dispatchNote->getNumReferencia(),
            'date_referencia' => $dispatchNote->getDateReferencia(),
            'status' => $dispatchNote->getStatus(),
            'cod_conductor' => $dispatchNote->getConductor()->getId(),
            'license_plate' => $dispatchNote->getLicensePlate(),
            'total_weight' => $dispatchNote->getTotalWeight(),
            'transfer_type' => $dispatchNote->getTransferType(),
            'vehicle_type' => $dispatchNote->getVehicleType(),
            'document_type_id' => $dispatchNote->getDocumentType() ? $dispatchNote->getDocumentType()->getId() : null,
            'destination_branch_client' => $dispatchNote->getdestination_branch_client(),
            'customer_id' => $dispatchNote->getCustomerId()
        ]);

        return new DispatchNote(
            id: $dispatchNote->id,
            company: $dispatchNote->company->toDomain($dispatchNote->company),
            branch: $dispatchNote->branch->toDomain($dispatchNote->branch),
            serie: $dispatchNote->serie,
            correlativo: $dispatchNote->correlativo,
            date: $dispatchNote->date,
            emission_reason: $dispatchNote->emission_reason->toDomain($dispatchNote->emission_reason),
            description: $dispatchNote->description,
            destination_branch: $dispatchNote->destination_branch->toDomain($dispatchNote->destination_branch),
            destination_address_customer: $dispatchNote->destination_address_customer,
            transport: $dispatchNote->transport->toDomain($dispatchNote->transport),
            observations: $dispatchNote->observations,
            num_orden_compra: $dispatchNote->num_orden_compra,
            doc_referencia: $dispatchNote->doc_referencia,
            num_referencia: $dispatchNote->num_referencia,
            date_referencia: $dispatchNote->date_referencia,
            status: $dispatchNote->status,
            conductor: $dispatchNote->conductor->toDomain($dispatchNote->conductor),
            license_plate: $dispatchNote->license_plate,
            total_weight: $dispatchNote->total_weight,
            transfer_type: $dispatchNote->transfer_type,
            vehicle_type: $dispatchNote->vehicle_type,
            document_type: $dispatchNote->document_type->toDomain($dispatchNote->document_type),
            destination_branch_client: $dispatchNote->destination_branch_client,
            customer_id: $dispatchNote->customer_id
        );
    }
  public function findById(int $id): ?DispatchNote
{
    $dispatchNote = EloquentDispatchNote::find($id);

    if (!$dispatchNote) {
        return null;
    }

    return new DispatchNote(
        id: $dispatchNote->id,
        company: $dispatchNote->company?->toDomain($dispatchNote->company),
        branch: $dispatchNote->branch->toDomain($dispatchNote->branch),
        serie: $dispatchNote->serie,
        correlativo: $dispatchNote->correlativo,
        date: $dispatchNote->date,
        emission_reason: $dispatchNote->emission_reason?->toDomain($dispatchNote->emission_reason),
        description: $dispatchNote->description,
        destination_branch: $dispatchNote->destination_branch?->toDomain($dispatchNote->destination_branch),
        destination_address_customer: $dispatchNote->destination_address_customer,
        transport: $dispatchNote->transport?->toDomain($dispatchNote->transport),
        observations: $dispatchNote->observations,
        num_orden_compra: $dispatchNote->num_orden_compra,
        doc_referencia: $dispatchNote->doc_referencia,
        num_referencia: $dispatchNote->num_referencia,
        date_referencia: $dispatchNote->date_referencia,
        status: $dispatchNote->status,
        conductor: $dispatchNote->conductor->toDomain($dispatchNote->conductor),
        license_plate: $dispatchNote->license_plate,
        total_weight: $dispatchNote->total_weight,
        transfer_type: $dispatchNote->transfer_type,
        vehicle_type: $dispatchNote->vehicle_type,
        document_type: $dispatchNote->document_type?->toDomain($dispatchNote->document_type),
        destination_branch_client: $dispatchNote->destination_branch_client,
        customer_id: $dispatchNote->customer_id
    );
}
   public function update(DispatchNote $dispatchNote):?DispatchNote{
         $dispatchNotess = EloquentDispatchNote::find($dispatchNote->getId());
        
           $dispatchNotess ->update([
            'cia_id' => $dispatchNote->getCompany() ? $dispatchNote->getCompany()->getId() : null,
            'branch_id' => $dispatchNote->getBranch() ? $dispatchNote->getBranch()->getId() : null,
            'serie' => $dispatchNote->getSerie(),
            'correlativo' => (
                ($dispatchNote->getDocumentType() ? strtoupper(substr($dispatchNote->getDocumentType()->getAbbreviation(), 0, 1)) : '') .
                '00' .
                ($dispatchNote->getBranch() ? strtoupper(substr($dispatchNote->getBranch()->getId(), 0, 1)) : '')
            ),

            'date' => $dispatchNote->getDate(),
            'emission_reason_id' => $dispatchNote->getEmissionReason() ? $dispatchNote->getEmissionReason()->getId() : null,
            'description' => $dispatchNote->getDescription(),
            'destination_branch_id' => $dispatchNote->getDestinationBranch() ? $dispatchNote->getDestinationBranch()->getId() : null,
            'destination_address_customer' => $dispatchNote->getDestinationAddressCustomer(),
            'transport_id' => $dispatchNote->getTransport() ? $dispatchNote->getTransport()->getId() : null,
            'observations' => $dispatchNote->getObservations(),
            'num_orden_compra' => $dispatchNote->getNumOrdenCompra(),
            'doc_referencia' => $dispatchNote->getDocReferencia(),
            'num_referencia' => $dispatchNote->getNumReferencia(),
            'date_referencia' => $dispatchNote->getDateReferencia(),
            'status' => $dispatchNote->getStatus(),
            'cod_conductor' => $dispatchNote->getConductor()->getId(),
            'license_plate' => $dispatchNote->getLicensePlate(),
            'total_weight' => $dispatchNote->getTotalWeight(),
            'transfer_type' => $dispatchNote->getTransferType(),
            'vehicle_type' => $dispatchNote->getVehicleType(),
            'document_type_id' => $dispatchNote->getDocumentType() ? $dispatchNote->getDocumentType()->getId() : null,
            'destination_branch_client' => $dispatchNote->getdestination_branch_client(),
            'customer_id' => $dispatchNote->getCustomerId()
        ]);

        return new DispatchNote(
            id: $dispatchNotess->id,
            company: $dispatchNotess->company->toDomain($dispatchNotess->company),
            branch: $dispatchNotess->branch->toDomain($dispatchNotess->branch),
            serie: $dispatchNotess->serie,
            correlativo: $dispatchNotess->correlativo,
            date: $dispatchNotess->date,
            emission_reason: $dispatchNotess->emission_reason->toDomain($dispatchNotess->emission_reason),
            description: $dispatchNotess->description,
            destination_branch: $dispatchNotess->destination_branch->toDomain($dispatchNotess->destination_branch),
            destination_address_customer: $dispatchNotess->destination_address_customer,
            transport: $dispatchNotess->transport->toDomain($dispatchNotess->transport),
            observations: $dispatchNotess->observations,
            num_orden_compra: $dispatchNotess->num_orden_compra,
            doc_referencia: $dispatchNotess->doc_referencia,
            num_referencia: $dispatchNotess->num_referencia,
            date_referencia: $dispatchNotess->date_referencia,
            status: $dispatchNotess->status,
            conductor: $dispatchNotess->conductor->toDomain($dispatchNotess->conductor),
            license_plate: $dispatchNotess->license_plate,
            total_weight: $dispatchNotess->total_weight,
            transfer_type: $dispatchNotess->transfer_type,
            vehicle_type: $dispatchNotess->vehicle_type,
            document_type: $dispatchNotess->document_type->toDomain($dispatchNotess->document_type),
            destination_branch_client: $dispatchNotess->destination_branch_client,
            customer_id: $dispatchNotess->customer_id
        );
         
   }
}