<?php

namespace App\Modules\PettyCashReceipt\Infrastructure\Persistence;

use App\Modules\PettyCashReceipt\Domain\Entities\PettyCashReceipt;
use App\Modules\PettyCashReceipt\Domain\Interface\PettyCashReceiptRepositoryInterface;
use App\Modules\PettyCashReceipt\Infrastructure\Models\EloquentPettyCashReceipt;
use Eloquent;
use Illuminate\Support\Facades\DB;

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
        // dd($pettyCashReceipt);
        $eloquentPettyCashReceipt = EloquentPettyCashReceipt::create([
            'company_id' => $pettyCashReceipt->getCompany(),
            'document_type' => $pettyCashReceipt->getDocumentType()->getId(),
            'series' => $pettyCashReceipt->getSeries(),
            'correlative' => $pettyCashReceipt->getCorrelative(),
            'date' => $pettyCashReceipt->getDate(),
            'delivered_to' => $pettyCashReceipt->getDeliveredTo(),
            'reason_code' => $pettyCashReceipt->getReasonCode()->getId(),
            'currency_type' => $pettyCashReceipt->getCurrencyType()->getId(),
            'amount' => $pettyCashReceipt->getAmount(),
            'observation' => $pettyCashReceipt->getObservation(),
            'status' => $pettyCashReceipt->getStatus(), 
            'branch_id' => $pettyCashReceipt->getBranch()->getId() 
        ]);

        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt?->company_id,
            document_type: $eloquentPettyCashReceipt->documentType?->toDomain($eloquentPettyCashReceipt->documentType),
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reasonCode?->toDomain($eloquentPettyCashReceipt->reasonCode),
            currency: $eloquentPettyCashReceipt->currency?->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            branch: $eloquentPettyCashReceipt->branch?->toDomain($eloquentPettyCashReceipt->branch)
        );
    } 
    public function findAll(?string $filter): array
    {
        $eloquentPettyCashReceipts = EloquentPettyCashReceipt::with(['reasonCode', 'documentType', 'branch', 'currency'])
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
                document_type: $eloquentPettyCashReceipt->documentType?->toDomain($eloquentPettyCashReceipt->documentType),
                series: $eloquentPettyCashReceipt->series,
                correlative: $eloquentPettyCashReceipt->correlative,
                date: $eloquentPettyCashReceipt->date,
                delivered_to: $eloquentPettyCashReceipt->delivered_to,
                reason_code: $eloquentPettyCashReceipt->reasonCode?->toDomain($eloquentPettyCashReceipt->reasonCode),
                currency: $eloquentPettyCashReceipt->currency?->toDomain($eloquentPettyCashReceipt->currency),
                amount: $eloquentPettyCashReceipt->amount,
                observation: $eloquentPettyCashReceipt->observation,
                status: $eloquentPettyCashReceipt->status, 
                branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)
            );
        })->toArray();
    }
    public function findById(int $id): ?PettyCashReceipt
    {
        $eloquentPettyCashReceipt = EloquentPettyCashReceipt::with(['documentType', 'reasonCode', 'currency', 'branch'])->find($id);
        if (!$eloquentPettyCashReceipt) {
            return null;
        }
        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt->company_id,
            document_type: $eloquentPettyCashReceipt->documentType?->toDomain($eloquentPettyCashReceipt->documentType),
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
            reason_code: $eloquentPettyCashReceipt->reasonCode?->toDomain($eloquentPettyCashReceipt->reasonCode),
            currency: $eloquentPettyCashReceipt->currency?->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status,
            branch: $eloquentPettyCashReceipt->branch?->toDomain($eloquentPettyCashReceipt->branch)

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
            'document_type' => $pettyCashReceipt->getDocumentType()->getId(),
            'series' => $pettyCashReceipt->getSeries(), 
            'date' => $pettyCashReceipt->getDate(),
            'delivered_to' => $pettyCashReceipt->getDeliveredTo(),
            'reason_code' => $pettyCashReceipt->getReasonCode()->getId(),
            'currency_type' => $pettyCashReceipt->getCurrencyType()->getId(),
            'amount' => $pettyCashReceipt->getAmount(),
            'observation' => $pettyCashReceipt->getObservation(),
            'status' => $pettyCashReceipt->getStatus(), 
        ]);

        return new PettyCashReceipt(
            id: $eloquentPettyCashReceipt->id,
            company_id: $eloquentPettyCashReceipt->company_id,
            document_type: $eloquentPettyCashReceipt->documentType?->toDomain($eloquentPettyCashReceipt->documentType),
            series: $eloquentPettyCashReceipt->series,
            correlative: $eloquentPettyCashReceipt->correlative,
            date: $eloquentPettyCashReceipt->date,
            delivered_to: $eloquentPettyCashReceipt->delivered_to,
             reason_code: $eloquentPettyCashReceipt->reasonCode?->toDomain($eloquentPettyCashReceipt->reasonCode),
            currency: $eloquentPettyCashReceipt->currency?->toDomain($eloquentPettyCashReceipt->currency),
            amount: $eloquentPettyCashReceipt->amount,
            observation: $eloquentPettyCashReceipt->observation,
            status: $eloquentPettyCashReceipt->status, 
            branch: $eloquentPettyCashReceipt->branch->toDomain($eloquentPettyCashReceipt->branch)
        );
    }
    public function updateStatus(int $pettyCashReceipt, int $status = 1): void
    {
        EloquentPettyCashReceipt::where('id', $pettyCashReceipt)->update(['status' => $status]);
    }
public function selectProcedure(
    $cia,
    $fecha,
    $fechaU,
    $nrocliente,
    $pcodsuc,
    $ptippag,
    $pcodban,
    $pnroope,
    $ptipdoc,
    $pserie,
    $pcorrelativo
): array {

    $resultado = DB::select(
        'CALL sp_parte_diario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            (int) $cia,
            $fecha,
            $fechaU,
            (int) $nrocliente,
            (int) $pcodsuc,
            (int) $ptippag,
            (int) $pcodban,
            $pnroope ?? '',
            (int) $ptipdoc,
            $pserie ?? '',
            $pcorrelativo ?? ''
        ]
    );

    return $resultado;
}


}
