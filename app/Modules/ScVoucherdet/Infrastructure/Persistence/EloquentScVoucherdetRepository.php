<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Persistence;

use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Models\EloquentScVoucherdet;

class EloquentScVoucherdetRepository implements ScVoucherdetRepositoryInterface
{
    public function create(ScVoucherdet $scVoucherdet): ?ScVoucherdet
    {
        $model = EloquentScVoucherdet::create([
            'cia' => $scVoucherdet->getCia(),
            'codcon' => $scVoucherdet->getCodcon()?->getId(),
            'tipdoc' => $scVoucherdet->getTipdoc(),
            'glosa' => $scVoucherdet->getGlosa(),
            'impsol' => $scVoucherdet->getImpsol(),
            'impdol' => $scVoucherdet->getImpdol(),
            'id_purchase' => $scVoucherdet->getIdPurchase(),
            'id_sc_voucher' => $scVoucherdet->getIdScVoucher(),
            'numdoc' => $scVoucherdet->getNumdoc(),
            'correlativo' => $scVoucherdet->getCorrelativo(),
            'serie' => $scVoucherdet->getSerie(),
        ]);

        return $model->load('paymentConcept')->toDomain();
    }

    public function update(ScVoucherdet $scVoucherdet): ?ScVoucherdet
    {
        $model = EloquentScVoucherdet::find($scVoucherdet->getId());

        if (!$model) {
            return null;
        }

        $model->update([
            'cia' => $scVoucherdet->getCia(),
            'codcon' => $scVoucherdet->getCodcon()?->getId(),
            'tipdoc' => $scVoucherdet->getTipdoc(),
            'glosa' => $scVoucherdet->getGlosa(),
            'impsol' => $scVoucherdet->getImpsol(),
            'impdol' => $scVoucherdet->getImpdol(),
            'id_purchase' => $scVoucherdet->getIdPurchase(),
            'numdoc' => $scVoucherdet->getNumdoc(),
            'correlativo' => $scVoucherdet->getCorrelativo(),
            'serie' => $scVoucherdet->getSerie(),
        ]);

        return $model->load('paymentConcept')->toDomain();
    }

    public function findById(int $id): ?ScVoucherdet
    {
        $model = EloquentScVoucherdet::with('paymentConcept')->find($id);
        return $model ? $model->toDomain() : null;
    }
    public function findAll(): array
    {
        return EloquentScVoucherdet::with('paymentConcept')->get()
            ->map(fn($model) => $model->toDomain())
            ->toArray();
    }

    public function findByVoucherId(int $voucherId): array
    {
        return EloquentScVoucherdet::with('paymentConcept')
            ->where('id_sc_voucher', $voucherId)
            ->get()
            ->map(fn($model) => $model->toDomain())
            ->toArray();
    }

    public function deleteByVoucherId(int $voucherId): void
    {
        EloquentScVoucherdet::where('id_sc_voucher', $voucherId)->delete();
    }
    public function getvoucherPurchase(int $id): array
    {
        return EloquentScVoucherdet::with('paymentConcept')
            ->where('id_purchase', $id)
            ->get()
            ->map(fn($model) => $model->toDomain())
            ->toArray();
    }
    public function findDetailByVoucherAndPurchase(int $voucherId, int $purchaseId): ?ScVoucherdet
    {
        $model = EloquentScVoucherdet::with('paymentConcept')
            ->where('id_sc_voucher', $voucherId)
            ->where('id_purchase', $purchaseId)
            ->first();

        return $model ? $model->toDomain() : null;
    }
}
