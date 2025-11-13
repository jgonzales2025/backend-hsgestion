<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Persistence;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Models\EloquentPettyCashReceipt;
use Eloquent;


class EloquentPettyCashReceiptRepository implements PettyCashReceiptRepositoryInterface
{
    public function getLastDocumentNumber(string $serie): ?string
    {
        $pettyCashReceipt = EloquentPettyCashReceipt::where('series', $serie)
            ->orderBy('correlative', 'desc')
            ->first();
        return $pettyCashReceipt?->correlative;
    }

    public function save(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt
    {
        $eloquentPettyCashReceipt = EloquentPettyCashReceipt::create([
            'company_id' => $pettyCashReceipt->getCompany(),
            'document_type' => $pettyCashReceipt->getDocumentType(),
            'series' => $pettyCashReceipt->getSeries(),
            'correlative' => $pettyCashReceipt->getCorrelative(),
            'date' => $pettyCashReceipt->getDate(),
            'delivered_to' => $pettyCashReceipt->getDeliveredTo(),
            'reason_code' => $pettyCashReceipt->getReasonCode(),
            'currency_type' => $pettyCashReceipt->getCurrencyType()->getId(),
            'amount' => $pettyCashReceipt->getAmount(),
            'observation' => $pettyCashReceipt->getObservation(),
            'status' => $pettyCashReceipt->getStatus(),
            'created_by' => $pettyCashReceipt->getCreatedBy(),
            'created_at_manual' => $pettyCashReceipt->getCreatedAtManual(),
            'updated_by' => $pettyCashReceipt->getUpdatedBy(),
            'updated_at_manual' => $pettyCashReceipt->getUpdatedAtManual(),
            'branch_id' => $pettyCashReceipt->getBranch()->getId()


        ]);

        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt?->company_id,
            document_type: $eloquentPettyCashReceipt->document_type,
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reason_code,
            currency: $eloquentPettyCashReceipt->currency->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            created_by: $eloquentPettyCashReceipt->created_by,
            created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
            updated_by: $eloquentPettyCashReceipt->updated_by,
            updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
            branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)
        );

    }
    public function findAll(?string $filter): array
    {
        $eloquentPettyCashReceipts = EloquentPettyCashReceipt::with(['branch', 'currency'])
            ->when(
                $filter,
                fn($q) =>
                $q->where(function ($q2) use ($filter) {
                    $q2->where('date', 'like', "%{$filter}%")
                        ->orWhere('correlative', 'like', "%{$filter}%");
                })
            )
            ->orderBy('id', 'desc')
            ->get();

        if (!$eloquentPettyCashReceipts) {
            return [];
        }
        return $eloquentPettyCashReceipts->map(function ($eloquentPettyCashReceipt) {
            return new PettyCashReceipt(
                id: $eloquentPettyCashReceipt->id,
                company_id: $eloquentPettyCashReceipt->company_id,
                document_type: $eloquentPettyCashReceipt->document_type,
                series: $eloquentPettyCashReceipt->series,
                correlative: $eloquentPettyCashReceipt->correlative,
                date: $eloquentPettyCashReceipt->date,
                delivered_to: $eloquentPettyCashReceipt->delivered_to,
                reason_code: $eloquentPettyCashReceipt->reason_code,
                currency: $eloquentPettyCashReceipt->currency?->toDomain($eloquentPettyCashReceipt->currency),
                amount: $eloquentPettyCashReceipt->amount,
                observation: $eloquentPettyCashReceipt->observation,
                status: $eloquentPettyCashReceipt->status,
                created_by: $eloquentPettyCashReceipt->created_by,
                created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
                updated_by: $eloquentPettyCashReceipt->updated_by,
                updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
                branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)
            );
        })->toArray();
    }
    public function findById(int $id): ?PettyCashReceipt
    {
        $eloquentPettyCashReceipt = EloquentPettyCashReceipt::find($id);
        if (!$eloquentPettyCashReceipt) {
            return null;
        }
        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt->company_id,
            document_type: $eloquentPettyCashReceipt->document_type,
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reason_code,
            currency: $eloquentPettyCashReceipt->currency->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            created_by: $eloquentPettyCashReceipt->created_by,
            created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
            updated_by: $eloquentPettyCashReceipt->updated_by,
            updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
            branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)

        );
    }
    public function update(PettyCashReceipt $pettyCashReceipt): ?PettyCashReceipt
    {
        $eloquentPettyCashReceipt = EloquentPettyCashReceipt::find($pettyCashReceipt->getId());
        if (!$eloquentPettyCashReceipt) {
            return null;
        }
        $eloquentPettyCashReceipt->update([
            'company_id' => $pettyCashReceipt->getCompany(),
            'document_type' => $pettyCashReceipt->getDocumentType(),
            'series' => $pettyCashReceipt->getSeries(),
            'correlative' => $pettyCashReceipt->getCorrelative(),
            'date' => $pettyCashReceipt->getDate(),
            'delivered_to' => $pettyCashReceipt->getDeliveredTo(),
            'reason_code' => $pettyCashReceipt->getReasonCode(),
            'currency_type' => $pettyCashReceipt->getCurrencyType()->getId(),
            'amount' => $pettyCashReceipt->getAmount(),
            'observation' => $pettyCashReceipt->getObservation(),
            'status' => $pettyCashReceipt->getStatus(),
            'created_by' => $pettyCashReceipt->getCreatedBy(),
            'created_at_manual' => $pettyCashReceipt->getCreatedAtManual(),
            'updated_by' => $pettyCashReceipt->getUpdatedBy(),
            'updated_at_manual' => $pettyCashReceipt->getUpdatedAtManual(),
        ]);

        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt->company_id,
            document_type: $eloquentPettyCashReceipt->document_type,
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reason_code,
            currency: $eloquentPettyCashReceipt->currency->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            created_by: $eloquentPettyCashReceipt->created_by,
            created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
            updated_by: $eloquentPettyCashReceipt->updated_by,
            updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
            branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)
        );
    }
}