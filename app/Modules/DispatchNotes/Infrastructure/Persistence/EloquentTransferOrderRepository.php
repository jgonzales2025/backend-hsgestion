<?php

namespace App\Modules\DispatchNotes\Infrastructure\Persistence;

use App\Modules\DispatchNotes\Domain\Interfaces\TransferOrderRepositoryInterface;
use App\Modules\DispatchNotes\Infrastructure\Models\EloquentDispatchNote;
use App\Modules\DispatchNotes\Domain\Entities\TransferOrder;
use Illuminate\Support\Facades\Log;

class EloquentTransferOrderRepository implements TransferOrderRepositoryInterface
{
    public function findAll(int $companyId, ?string $description, ?string $startDate, ?string $endDate, ?int $status, ?int $emissionReasonId)
    {
        $eloquentDispatchNotes = EloquentDispatchNote::where('document_type_id', 21)
            ->where('cia_id', $companyId)
            ->where('emission_reason_id', 26)
            ->when($description, function ($query) use ($description) {
                return $query->where(function ($q) use ($description) {
                    $q->where('correlativo', 'like', '%' . $description . '%')
                        ->orWhereHas('emission_reason', function ($query) use ($description) {
                            $query->where('description', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('branch', function ($query) use ($description) {
                            $query->where('name', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('destination_branch', function ($query) use ($description) {
                            $query->where('name', 'like', '%' . $description . '%');
                        });
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('created_at', '<=', $endDate);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($emissionReasonId, function ($query) use ($emissionReasonId) {
                $query->where('emission_reason_id', $emissionReasonId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eloquentDispatchNotes->getCollection()->transform(function ($item) {
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
                stage: $item->stage,
                transfer_date: $item->transfer_date,
                arrival_date: $item->arrival_date,
            );
        });

        return $eloquentDispatchNotes;
    }

    public function findAllConsignations(int $companyId, ?string $description, ?string $startDate, ?string $endDate, ?int $status, ?int $emissionReasonId)
    {
        $eloquentDispatchNotes = EloquentDispatchNote::where('document_type_id', 21)
            ->where('cia_id', $companyId)
            ->where('emission_reason_id', 27)
            ->when($description, function ($query) use ($description) {
                return $query->where(function ($q) use ($description) {
                    $q->where('correlativo', 'like', '%' . $description . '%')
                        ->orWhereHas('emission_reason', function ($query) use ($description) {
                            $query->where('description', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('branch', function ($query) use ($description) {
                            $query->where('name', 'like', '%' . $description . '%');
                        })
                        ->orWhereHas('destination_branch', function ($query) use ($description) {
                            $query->where('name', 'like', '%' . $description . '%');
                        });
                });
            })
            ->when($startDate, function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->where('created_at', '<=', $endDate);
            })
            ->when($status !== null, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($emissionReasonId, function ($query) use ($emissionReasonId) {
                $query->where('emission_reason_id', $emissionReasonId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $eloquentDispatchNotes->getCollection()->transform(function ($item) {
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
                stage: $item->stage,
                transfer_date: $item->transfer_date,
                arrival_date: $item->arrival_date,
            );
        });

        return $eloquentDispatchNotes;
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
            'stage' => $transferOrder->getStage(),
            'transfer_date' => now()->toDateString()
        ]);
        Log::info($eloquentDispatchNote->status);
        return new TransferOrder(
            id: $eloquentDispatchNote->id,
            company: $transferOrder->getCompany(),
            branch: $transferOrder->getBranch(),
            serie: $eloquentDispatchNote->serie,
            correlative: $eloquentDispatchNote->correlativo,
            emission_reason: $transferOrder->getEmissionReason(),
            destination_branch: $transferOrder->getDestinationBranch(),
            observations: $eloquentDispatchNote->observations,
            status: $eloquentDispatchNote->status,
            stage: $eloquentDispatchNote->stage,
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
            stage: $transferOrder->stage,
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
            'stage' => 1,
            'arrival_date' => now()->toDateString(),
        ]);
    }

    public function toInvalidate(int $transferOrderId): void
    {
        $transferOrder = EloquentDispatchNote::find($transferOrderId);
        $transferOrder->update([
            'stage' => 2,
            'status' => 0
        ]);
    }
}