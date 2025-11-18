<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;

class EloquentTransferOrderRepository implements TransferOrderRepositoryInterface
{
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
        );
    }
    public function getLastDocumentNumber(string $serie): ?string
    {
        $lastTransferOrder = EloquentDispatchNote::where('serie', $serie)
            ->orderBy('correlativo', 'desc')
            ->first();
        return $lastTransferOrder?->correlativo;
    }
}