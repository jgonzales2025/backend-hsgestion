<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

class EloquentTransferOrderRepository implements TransferOrderRepositoryInterface
{
    public function findAll(int $companyId): array
    {
        return EloquentDispatchNote::where('document_type_id', 21)
            ->where('cia_id', $companyId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return new TransferOrder(
                    id: $item->id,
                    company: $item->company->toDomain($item->company),
                    branch: $item->branch->toDomain($item->branch),
                    serie: $item->serie,
                    correlative: $item->correlativo,
                    emission_reason: $item->emission_reason->toDomain($item->emission_reason),
                    destination_branch: $item->destination_branch->toDomain($item->destination_branch),
                    observations: $item->observations,
                    status: $item->status,
                    transfer_date: $item->transfer_date,
                    arrival_date: $item->arrival_date,
                );
            })
            ->toArray();
    }

    public function save(TransferOrder $transferOrder): TransferOrder
    {
        $eloquentDispatchNote = EloquentDispatchNote::create([
            'cia_id' => $transferOrder->getCompany()->getId(),
            'branch_id' => $transferOrder->getBranch()->getId(),
            'document_type_id' => 21,
            'serie' => $transferOrder->getSerie(),
            'correlativo' => $transferOrder->getCorrelative(),
            'emission_reason_id' => $transferOrder->getEmissionReason()->getId(),
            'destination_branch_id' => $transferOrder->getDestinationBranch()->getId(),
            'observations' => $transferOrder->getObservations(),
            'status' => $transferOrder->getStatus(),
            'transfer_date' => now()->toDateString()
        ]);

        return new TransferOrder(
            id: $eloquentDispatchNote->id,
            company: $transferOrder->getCompany(),
            branch: $transferOrder->getBranch(),
            serie: $eloquentDispatchNote->serie,
            correlative: $eloquentDispatchNote->correlativo,
            emission_reason: $transferOrder->getEmissionReason(),
            destination_branch: $transferOrder->getDestinationBranch(),
            observations: $eloquentDispatchNote->observations,
            status: $transferOrder->getStatus(),
            transfer_date: $eloquentDispatchNote->transfer_date
        );
    }
    public function getLastDocumentNumber(string $serie): ?string
    {
        $lastTransferOrder = EloquentDispatchNote::where('serie', $serie)
            ->orderBy('correlativo', 'desc')
            ->first();
        return $lastTransferOrder?->correlativo;
    }

    public function findById(int $id): ?TransferOrder
    {
        $transferOrder = EloquentDispatchNote::find($id);

        if (!$transferOrder) {
            return null;
        }
        
        return new TransferOrder(
            id: $transferOrder->id,
            company: $transferOrder->company->toDomain($transferOrder->company),
            branch: $transferOrder->branch->toDomain($transferOrder->branch),
            serie: $transferOrder->serie,
            correlative: $transferOrder->correlativo,
            emission_reason: $transferOrder->emission_reason->toDomain($transferOrder->emission_reason),
            destination_branch: $transferOrder->destination_branch->toDomain($transferOrder->destination_branch),
            observations: $transferOrder->observations,
            status: $transferOrder->status,
            transfer_date: $transferOrder->transfer_date,
            arrival_date: $transferOrder->arrival_date,
        );
    }

    public function update(int $id, TransferOrder $transferOrder): void
    {
        $eloquentTransferOrder = EloquentDispatchNote::find($id);

        $eloquentTransferOrder->update([
            'branch_id' => $transferOrder->getBranch()->getId(),
            'emission_reason_id' => $transferOrder->getEmissionReason()->getId(),
            'destination_branch_id' => $transferOrder->getDestinationBranch()->getId(),
            'observations' => $transferOrder->getObservations(),
        ]);
    }

    public function updateStatusTransferOrder(int $transferOrderId): void
    {
        $transferOrder = EloquentDispatchNote::find($transferOrderId);
        $transferOrder->update([
            'status' => 1,
            'arrival_date' => now()->toDateString(),
        ]);
    }
}