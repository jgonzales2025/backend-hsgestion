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
            'document_type',
            'supplier',
            'address_supplier',
        ])->orderByDesc('id')
          ->get();

        return $dispatchNotes->map(fn($dispatchNote) => $this->mapToDomain($dispatchNote))->toArray();
    }

    public function getLastDocumentNumber(): ?string
    {
        $dispatch = EloquentDispatchNote::all()
            ->sortByDesc('correlativo')
            ->first();

        return $dispatch?->correlativo;
    }

    public function save(DispatchNote $dispatchNote): ?DispatchNote
    {
        $dispatchNotes = EloquentDispatchNote::create($this->mapToArray($dispatchNote));

        return $this->buildDomainDispatchNote($dispatchNotes, $dispatchNote);
    }
    public function findById(int $id): ?DispatchNote
    {
        $dispatchNote = EloquentDispatchNote::find($id);
        if (!$dispatchNote) {
            return null;
        }

        return $this->mapToDomain($dispatchNote);
    }

    public function update(DispatchNote $dispatchNote): ?DispatchNote
    {
        $dispatchNotess = EloquentDispatchNote::find($dispatchNote->getId());

        $dispatchNotess->update($this->mapToArray($dispatchNote));
        return $this->buildDomainDispatchNote($dispatchNotess, $dispatchNote);
    }
    private function mapToDomain(EloquentDispatchNote $dispatchNote): DispatchNote
    {
        return new DispatchNote(
            id: $dispatchNote->id,
            company: $dispatchNote->company->toDomain($dispatchNote->company),
            branch: $dispatchNote->branch->toDomain($dispatchNote->branch),
            serie: $dispatchNote->serie,
            correlativo: $dispatchNote->correlativo,
            emission_reason: $dispatchNote->emission_reason->toDomain($dispatchNote->emission_reason),
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
            conductor: $dispatchNote->conductor?->toDomain($dispatchNote->conductor),
            license_plate: $dispatchNote->license_plate,
            total_weight: $dispatchNote->total_weight,
            transfer_type: $dispatchNote->transfer_type,
            vehicle_type: $dispatchNote->vehicle_type,
            reference_document_type: $dispatchNote->reference_document_type?->toDomain($dispatchNote->reference_document_type),
            destination_branch_client: $dispatchNote->destination_branch_client,
            customer_id: $dispatchNote->customer_id,
            supplier: $dispatchNote->supplier?->toDomain($dispatchNote->supplier),
            address_supplier: $dispatchNote->address_supplier?->toDomain($dispatchNote->address_supplier),
            created_at: $dispatchNote->created_at ? $dispatchNote->created_at->format('Y-m-d H:i:s') : null
        );
    }
   private function buildDomainDispatchNote(EloquentDispatchNote $eloquentDispatchNote,DispatchNote $dispatchNote): DispatchNote
    {
       return new DispatchNote(
            id: $eloquentDispatchNote->id,
            company: $dispatchNote->getCompany(),
            branch: $dispatchNote->getBranch(),
            serie: $eloquentDispatchNote->serie,
            correlativo: $eloquentDispatchNote->correlativo,
            emission_reason: $dispatchNote->getEmissionReason(),
            description: $eloquentDispatchNote->description,
            destination_branch: $dispatchNote->getDestinationBranch(),
            destination_address_customer: $dispatchNote->getDestinationAddressCustomer(),
            transport: $dispatchNote->getTransport(),
            observations: $eloquentDispatchNote->observations,
            num_orden_compra: $eloquentDispatchNote->num_orden_compra,
            doc_referencia: $eloquentDispatchNote->doc_referencia,
            num_referencia: $eloquentDispatchNote->num_referencia,
            date_referencia: $eloquentDispatchNote->date_referencia,
            status: $eloquentDispatchNote->status,
            conductor: $dispatchNote->getConductor(),
            license_plate: $eloquentDispatchNote->license_plate,
            total_weight: $eloquentDispatchNote->total_weight,
            transfer_type: $eloquentDispatchNote->transfer_type,
            vehicle_type: $eloquentDispatchNote->vehicle_type,
            reference_document_type: $dispatchNote->getReferenceDocumentType(),
            destination_branch_client: $eloquentDispatchNote->destination_branch_client,
            customer_id: $eloquentDispatchNote->customer_id,
            supplier: $dispatchNote->getSupplier(),
            address_supplier: $dispatchNote->getAddressSupplier(),
            created_at: $eloquentDispatchNote->created_at ? $eloquentDispatchNote->created_at->format('Y-m-d H:i:s') : null
       );
    }
    private function mapToArray(DispatchNote $dispatchNote){
            return ['cia_id' => $dispatchNote->getCompany() ? $dispatchNote->getCompany()->getId() : null,
            'branch_id' => $dispatchNote->getBranch() ? $dispatchNote->getBranch()->getId() : null,
            'document_type_id' => 9,
            'serie' => $dispatchNote->getSerie(),
            'correlativo' => $dispatchNote->getCorrelativo(),
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
            'cod_conductor' => $dispatchNote->getConductor() ? $dispatchNote->getConductor()->getId() : null,
            'license_plate' => $dispatchNote->getLicensePlate(),
            'total_weight' => $dispatchNote->getTotalWeight(),
            'transfer_type' => $dispatchNote->getTransferType(),
            'vehicle_type' => $dispatchNote->getVehicleType(),
            'reference_document_type_id' => $dispatchNote->getReferenceDocumentType() ? $dispatchNote->getReferenceDocumentType()->getId() : null,
            'destination_branch_client' => $dispatchNote->getdestination_branch_client(),
            'customer_id' => $dispatchNote->getCustomerId(),
            'supplier_id' => $dispatchNote->getSupplier()?->getId() ?? null,
            'address_supplier_id' => $dispatchNote->getAddressSupplier()?->getId() ?? null
    ];
    }
    public function updateStatus(int $dispatchNote,int $status): void
    {
     EloquentDispatchNote::where('id', $dispatchNote)->update(['status' => $status]);
    
    }

}