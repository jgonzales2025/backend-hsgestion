<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;
use App\Modules\DispatchNotes\Domain\Interfaces\DispatchNotesRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentDIspatchNoteRepository implements DispatchNotesRepositoryInterface
{
    public function findAll(?string $description, ?int $status, ?int $emissionReasonId, ?string $estadoSunat = null): LengthAwarePaginator
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
            'referenceDocumentType',
        ])
            ->orderByDesc('id')
            ->where('document_type_id', '!=', 21)
            ->when($description, function ($query) use ($description) {
                return $query->where(function ($q) use ($description) {
                    $q->where('correlativo', 'like', '%' . $description . '%')
                        ->orWhere('license_plate', 'like', '%' . $description . '%')
                        ->orWhereHas('emission_reason', function ($query) use ($description) {
                            $query->where('description', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('transport', function ($query) use ($description) {
                            $query->where('company_name', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('conductor', function ($query) use ($description) {
                            $query->where('name', 'like', '%' . $description . '%');
                        });
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when($emissionReasonId, function ($query) use ($emissionReasonId) {
                return $query->where('emission_reason_id', $emissionReasonId);
            })
            ->when($estadoSunat, fn($q) => $q->where('estado_sunat', $estadoSunat))
            ->paginate(10);

        $dispatchNotes->getCollection()->transform(
            fn($dispatchNote) => $this->mapToDomain($dispatchNote)
        );

        return $dispatchNotes;
    }
    public function findAllExcel(
        ?string $description,
        ?int $status,
        ?int $emissionReasonId,
        ?string $estadoSunat = null
    ): Collection {
        return EloquentDispatchNote::with([
            'company',
            'branch',
            'destination_branch',
            'emission_reason',
            'transport',
            'conductor',
            'document_type',
            'supplier',
            'address_supplier',
            'referenceDocumentType',
        ])
            ->orderByDesc('id')
            ->where('document_type_id', '!=', 21)
            ->when($description, function ($query) use ($description) {
                $query->where(function ($q) use ($description) {
                    $q->where('correlativo', 'like', "%$description%")
                        ->orWhere('license_plate', 'like', "%$description%");
                });
            })
            ->when($status !== null, fn($q) => $q->where('status', $status))
            ->when($emissionReasonId, fn($q) => $q->where('emission_reason_id', $emissionReasonId))
            ->when($estadoSunat, fn($q) => $q->where('estado_sunat', $estadoSunat))
            ->get()
            ->map(fn($dispatchNote) => $this->mapToDomain($dispatchNote));
    }


    public function getLastDocumentNumber(string $serie): ?string
    {
        $dispatch = EloquentDispatchNote::where('serie', $serie)
            ->orderBy('correlativo', 'desc')
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

    public function findByDocumentSale(string $serie, string $correlative): ?DispatchNote
    {
        $eloquentDispatch = EloquentDispatchNote::where('doc_referencia', $serie)->where('num_referencia', $correlative)->first();
        if (!$eloquentDispatch) {
            return null;
        }

        return $this->mapToDomain($eloquentDispatch);
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
            destination_branch_client: $dispatchNote->destination_branch_client,
            customer_id: $dispatchNote->customer_id,
            supplier: $dispatchNote->supplier?->toDomain($dispatchNote->supplier),
            address_supplier: $dispatchNote->address_supplier?->toDomain($dispatchNote->address_supplier),
            reference_document_type: $dispatchNote->referenceDocumentType?->toDomain($dispatchNote->referenceDocumentType),
            created_at: $dispatchNote->created_at ? $dispatchNote->created_at->format('Y-m-d H:i:s') : null,
            estado_sunat: $dispatchNote->estado_sunat
        );
    }
    private function buildDomainDispatchNote(EloquentDispatchNote $eloquentDispatchNote, DispatchNote $dispatchNote): DispatchNote
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
            destination_branch_client: $eloquentDispatchNote->destination_branch_client,
            customer_id: $eloquentDispatchNote->customer_id,
            supplier: $dispatchNote->getSupplier(),
            address_supplier: $dispatchNote->getAddressSupplier(),
            reference_document_type: $dispatchNote->getReferenceDocumentType(),
            created_at: $eloquentDispatchNote->created_at ? $eloquentDispatchNote->created_at->format('Y-m-d H:i:s') : null
        );
    }
    private function mapToArray(DispatchNote $dispatchNote)
    {
        return [
            'cia_id' => $dispatchNote->getCompany() ? $dispatchNote->getCompany()->getId() : null,
            'branch_id' => $dispatchNote->getBranch() ? $dispatchNote->getBranch()->getId() : null,
            'document_type_id' => 9,
            'serie' => $dispatchNote->getSerie(),
            'correlativo' => $dispatchNote->getCorrelativo(),
            'emission_reason_id' => $dispatchNote->getEmissionReason() ? $dispatchNote->getEmissionReason()->getId() : null,
            'description' => $dispatchNote->getDescription(),
            'destination_branch_id' => $dispatchNote->getDestinationBranch() ? $dispatchNote->getDestinationBranch()->getId() : null,
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
            'destination_branch_client' => $dispatchNote->getdestination_branch_client(),
            'customer_id' => $dispatchNote->getCustomerId(),
            'supplier_id' => $dispatchNote->getSupplier()?->getId() ?? null,
            'address_supplier_id' => $dispatchNote->getAddressSupplier()?->getId() ?? null,
            'reference_document_type_id' => $dispatchNote->getReferenceDocumentType()?->getId() ?? null,
        ];
    }
    public function updateStatus(int $dispatchNote, int $status): void
    {
        EloquentDispatchNote::where('id', $dispatchNote)->update(['status' => $status]);
    }

    public function findByDocument(string $serie, string $correlative): ?DispatchNote
    {
        $dispatchNote = EloquentDispatchNote::where('serie', $serie)->where('correlativo', $correlative)
            ->whereIn('emission_reason_id', [1, 5])
            ->whereNull('doc_referencia')
            ->whereNull('num_referencia')
            ->first();

        if (!$dispatchNote) {
            return null;
        }

        return $this->mapToDomain($dispatchNote);
    }
}
