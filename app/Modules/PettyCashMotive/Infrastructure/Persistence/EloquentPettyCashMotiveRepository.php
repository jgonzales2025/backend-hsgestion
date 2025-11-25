<?php

namespace App\Modules\PettyCashMotive\Infrastructure\Persistence;

use App\Modules\PettyCashMotive\Domain\Entities\PettyCashMotive;
use App\Modules\PettyCashMotive\Domain\Interface\PettyCashMotiveInterfaceRepository;
use App\Modules\PettyCashMotive\Infrastructure\Models\EloquentPettyCashMotive;

class EloquentPettyCashMotiveRepository implements PettyCashMotiveInterfaceRepository
{

    public function save(?PettyCashMotive $pettyCashMotive): ?PettyCashMotive
    {
        $eloquentPettyCashMotive = EloquentPettyCashMotive::create([
            'company_id' => $pettyCashMotive->getCompanyId(),
            'description' => $pettyCashMotive->getDescription(),
            'receipt_type' => $pettyCashMotive->getReceiptType()->getId(),
            'user_id' => $pettyCashMotive->getUserId(),
            'status' => $pettyCashMotive->getStatus(),
        ]);

        return new PettyCashMotive(
            id: $eloquentPettyCashMotive->id,
            company_id: $eloquentPettyCashMotive->company_id,
            description: $eloquentPettyCashMotive->description,
            receipt_type: $eloquentPettyCashMotive->documentType?->toDomain($eloquentPettyCashMotive->documentType),
            user_id: $eloquentPettyCashMotive->user_id,
            status: $eloquentPettyCashMotive->status,
        );
    }
    public function update(?PettyCashMotive $pettyCashMotive): ?PettyCashMotive
    {
        $eloquentPettyCashMotive = EloquentPettyCashMotive::find($pettyCashMotive->getId());
        if (!$eloquentPettyCashMotive) {
            return null;
        }
        $eloquentPettyCashMotive->update([
            'company_id' => $pettyCashMotive->getCompanyId(),
            'description' => $pettyCashMotive->getDescription(),
            'receipt_type' => $pettyCashMotive->getReceiptType()->getId(),
            'user_id' => $pettyCashMotive->getUserId(),
            'status' => $pettyCashMotive->getStatus(),
        ]);
        return new PettyCashMotive(
            id: $eloquentPettyCashMotive->id,
            company_id: $eloquentPettyCashMotive->company_id,
            description: $eloquentPettyCashMotive->description,
            receipt_type: $eloquentPettyCashMotive->documentType?->toDomain($eloquentPettyCashMotive->documentType),
            user_id: $eloquentPettyCashMotive->user_id,
            status: $eloquentPettyCashMotive->status,
        );
    }
    public function findAll(?string $receipt_type): array
    {
        $eloquentPettyCashMotive = EloquentPettyCashMotive::with(['documentType'])
            ->when($receipt_type, fn($q) => $q->where('receipt_type', $receipt_type))
            ->get();

        return $eloquentPettyCashMotive->map(function ($eloquentPettyCashMotive) {

            return new PettyCashMotive(
                id: $eloquentPettyCashMotive->id,
                company_id: $eloquentPettyCashMotive->company_id,
                description: $eloquentPettyCashMotive->description,
                receipt_type: $eloquentPettyCashMotive->documentType?->toDomain($eloquentPettyCashMotive->documentType),
                user_id: $eloquentPettyCashMotive->user_id,
                status: $eloquentPettyCashMotive->status,
            );
        })->toArray();
    }
    public function findById(int $id): ?PettyCashMotive
    {
        $eloquentPettyCashMotive = EloquentPettyCashMotive::with(['documentType'])->find($id);
        if (!$eloquentPettyCashMotive) {
            return null;
        }
        return new PettyCashMotive(
            id: $eloquentPettyCashMotive->id,
            company_id: $eloquentPettyCashMotive->company_id,
            description: $eloquentPettyCashMotive->description,
            receipt_type: $eloquentPettyCashMotive->documentType?->toDomain($eloquentPettyCashMotive->documentType),
            user_id: $eloquentPettyCashMotive->user_id,
            status: $eloquentPettyCashMotive->status,
        );
    }
}
