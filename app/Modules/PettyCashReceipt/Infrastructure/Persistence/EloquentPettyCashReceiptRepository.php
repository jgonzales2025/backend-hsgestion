<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Persistence;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Models\EloquentPettyCashReceipt;
use Eloquent;


class EloquentPettyCashReceiptRepository implements PettyCashReceiptRepositoryInterface
{

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
            'currency_type' => $pettyCashReceipt->getCurrencyType(),
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
            company: $eloquentPettyCashReceipt->company,
            document_type: $eloquentPettyCashReceipt->document_type,
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reason_code,
            currency_type: $eloquentPettyCashReceipt->currency_type,
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            created_by: $eloquentPettyCashReceipt->created_by,
            created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
            updated_by: $eloquentPettyCashReceipt->updated_by,
            updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
        );

    }
    public function findAll(): array
    {
        $eloquentPettyCashReceipts = EloquentPettyCashReceipt::all();
        if (!$eloquentPettyCashReceipts) {
            return [];
        }
        return $eloquentPettyCashReceipts->map(function ($eloquentPettyCashReceipt) {
            return new PettyCashReceipt(
                id: $eloquentPettyCashReceipt->id,
                company: $eloquentPettyCashReceipt->company,
                document_type: $eloquentPettyCashReceipt->document_type,
                series: $eloquentPettyCashReceipt->series,
                correlative: $eloquentPettyCashReceipt->correlative,
                date: $eloquentPettyCashReceipt->date,
                delivered_to: $eloquentPettyCashReceipt->delivered_to,
                reason_code: $eloquentPettyCashReceipt->reason_code,
                currency_type: $eloquentPettyCashReceipt->currency_type,
                amount: $eloquentPettyCashReceipt->amount,
                observation: $eloquentPettyCashReceipt->observation,
                status: $eloquentPettyCashReceipt->status,
                created_by: $eloquentPettyCashReceipt->created_by,
                created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
                updated_by: $eloquentPettyCashReceipt->updated_by,
                updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,
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
            company: $eloquentPettyCashReceipt->company,
            document_type: $eloquentPettyCashReceipt->document_type,
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reason_code,
            currency_type: $eloquentPettyCashReceipt->currency_type,
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            created_by: $eloquentPettyCashReceipt->created_by,
            created_at_manual: $eloquentPettyCashReceipt->created_at_manual,
            updated_by: $eloquentPettyCashReceipt->updated_by,
            updated_at_manual: $eloquentPettyCashReceipt->updated_at_manual,

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
            'currency_type' => $pettyCashReceipt->getCurrencyType(),
            'amount' => $pettyCashReceipt->getAmount(),
            'observation' => $pettyCashReceipt->getObservation(),
            'status' => $pettyCashReceipt->getStatus(),
            'created_by' => $pettyCashReceipt->getCreatedBy(),
            'created_at_manual' => $pettyCashReceipt->getCreatedAtManual(),
            'updated_by' => $pettyCashReceipt->getUpdatedBy(),
            'updated_at_manual' => $pettyCashReceipt->getUpdatedAtManual(),
        ]);

        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->getId(),
            company: $eloquentPettyCashReceipt->getCompany(),
            document_type: $eloquentPettyCashReceipt->getDocumentType(),
            series: $eloquentPettyCashReceipt->getSeries(),
            correlative: $eloquentPettyCashReceipt->getCorrelative(),
            date: $eloquentPettyCashReceipt->getDate(),
            delivered_to: $eloquentPettyCashReceipt->getDeliveredTo(),
            reason_code: $eloquentPettyCashReceipt->getReasonCode(),
            currency_type: $eloquentPettyCashReceipt->getCurrencyType(),
            amount: $eloquentPettyCashReceipt->getAmount(),
            observation: $eloquentPettyCashReceipt->getObservation(),
            status: $eloquentPettyCashReceipt->getStatus(),
            created_by: $eloquentPettyCashReceipt->getCreatedBy(),
            created_at_manual: $eloquentPettyCashReceipt->getCreatedAtManual(),
            updated_by: $eloquentPettyCashReceipt->getUpdatedBy(),
            updated_at_manual: $eloquentPettyCashReceipt->getUpdatedAtManual(),
        );
    }
}