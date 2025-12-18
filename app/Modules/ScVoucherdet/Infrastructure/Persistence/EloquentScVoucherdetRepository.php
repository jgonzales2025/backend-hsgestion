<?php

namespace App\Modules\ScVoucherdet\Infrastructure\Persistence;

use App\Modules\ScVoucherdet\Domain\Entities\ScVoucherdet;
use App\Modules\ScVoucherdet\Domain\Interface\ScVoucherdetRepositoryInterface;
use App\Modules\ScVoucherdet\Infrastructure\Models\EloquentScVoucherdet;

class EloquentScVoucherdetRepository implements ScVoucherdetRepositoryInterface
{
    public function create(ScVoucherdet $scVoucherdet): ?ScVoucherdet
    {
        $scVoucherdet = EloquentScVoucherdet::create([
            'cia' => $scVoucherdet->getCia(),
            'codcon' => $scVoucherdet->getCodcon(),
            'tipdoc' => $scVoucherdet->getTipdoc(),

            'glosa' => $scVoucherdet->getGlosa(),
            'impsol' => $scVoucherdet->getImpsol(),
            'impdol' => $scVoucherdet->getImpdol(),
            'impdol' => $scVoucherdet->getImpdol(),
            'id_purchase' => $scVoucherdet->getIdPurchase(),
            'id_sc_voucher' => $scVoucherdet->getIdScVoucher(),
            'numdoc' => $scVoucherdet->getNumdoc(),
            'correlativo' => $scVoucherdet->getCorrelativo(),
            'serie' => $scVoucherdet->getSerie(),
        ]);

        return new ScVoucherdet(
            id: $scVoucherdet->id,
            cia: $scVoucherdet->cia,
            codcon: $scVoucherdet->codcon,
            tipdoc: $scVoucherdet->tipdoc,

            glosa: $scVoucherdet->glosa,
            impsol: $scVoucherdet->impsol,
            impdol: $scVoucherdet->impdol,
            id_purchase: $scVoucherdet->id_purchase,
            id_sc_voucher: $scVoucherdet->id_sc_voucher,
            numdoc: $scVoucherdet->numdoc,
            correlativo: $scVoucherdet->correlativo,
            serie: $scVoucherdet->serie,
        );
    }
    public function update(ScVoucherdet $scVoucherdet): ?ScVoucherdet
    {
        $scVoucherdet = EloquentScVoucherdet::find($scVoucherdet->getId());

        $scVoucherdetA = $scVoucherdet->update([
            'cia' => $scVoucherdet->getCia(),
            'codcon' => $scVoucherdet->getCodcon(),
            'tipdoc' => $scVoucherdet->getTipdoc(),

            'glosa' => $scVoucherdet->getGlosa(),
            'impsol' => $scVoucherdet->getImpsol(),
            'impdol' => $scVoucherdet->getImpdol(),
            'id_purchase' => $scVoucherdet->getIdPurchase(),
            'numdoc' => $scVoucherdet->getNumdoc(),
            'correlativo' => $scVoucherdet->getCorrelativo(),
            'serie' => $scVoucherdet->getSerie(),
        ]);

        return new ScVoucherdet(
            id: $scVoucherdetA->id,
            cia: $scVoucherdetA->cia,
            codcon: $scVoucherdetA->codcon,
            tipdoc: $scVoucherdetA->tipdoc,

            glosa: $scVoucherdetA->glosa,
            impsol: $scVoucherdetA->impsol,
            impdol: $scVoucherdetA->impdol,
            id_purchase: $scVoucherdetA->id_purchase,
            id_sc_voucher: $scVoucherdetA->id_sc_voucher,
            numdoc: $scVoucherdet->numdoc,
            correlativo: $scVoucherdet->correlativo,
            serie: $scVoucherdet->serie,
        );
    }

    public function findById(int $id): ?ScVoucherdet
    {
        $scVoucherdet = EloquentScVoucherdet::find($id);
        return new ScVoucherdet(
            id: $scVoucherdet->id,
            cia: $scVoucherdet->cia,
            codcon: $scVoucherdet->codcon,
            tipdoc: $scVoucherdet->tipdoc,

            glosa: $scVoucherdet->glosa,
            impsol: $scVoucherdet->impsol,
            impdol: $scVoucherdet->impdol,
            id_purchase: $scVoucherdet->id_purchase,
            id_sc_voucher: $scVoucherdet->id_sc_voucher,
            numdoc: $scVoucherdet->numdoc,
            correlativo: $scVoucherdet->correlativo,
            serie: $scVoucherdet->serie,
        );
    }
    public function findAll(): array
    {
        $scVoucherdet = EloquentScVoucherdet::all();
        return $scVoucherdet->map(function ($scVoucherdet) {
            return new ScVoucherdet(
                id: $scVoucherdet->id,
                cia: $scVoucherdet->cia,
                codcon: $scVoucherdet->codcon,
                tipdoc: $scVoucherdet->tipdoc,

                glosa: $scVoucherdet->glosa,
                impsol: $scVoucherdet->impsol,
                impdol: $scVoucherdet->impdol,
                id_purchase: $scVoucherdet->id_purchase,
                id_sc_voucher: $scVoucherdet->id_sc_voucher,
                numdoc: $scVoucherdet->numdoc,
                correlativo: $scVoucherdet->correlativo,
                serie: $scVoucherdet->serie,
            );
        })->toArray();
    }

    public function findByVoucherId(int $voucherId): array
    {
        $scVoucherdetCollection = EloquentScVoucherdet::where('id_sc_voucher', $voucherId)->get();

        return $scVoucherdetCollection->map(function ($scVoucherdet) {
            return new ScVoucherdet(
                id: $scVoucherdet->id,
                cia: $scVoucherdet->cia,
                codcon: $scVoucherdet->codcon,
                tipdoc: $scVoucherdet->tipdoc,
                glosa: $scVoucherdet->glosa,
                impsol: $scVoucherdet->impsol,
                impdol: $scVoucherdet->impdol,
                id_purchase: $scVoucherdet->id_purchase,
                id_sc_voucher: $scVoucherdet->id_sc_voucher,
                numdoc: $scVoucherdet->numdoc,
                correlativo: $scVoucherdet->correlativo,
                serie: $scVoucherdet->serie,
            );
        })->toArray();
    }

    public function deleteByVoucherId(int $voucherId): void
    {
        EloquentScVoucherdet::where('id_sc_voucher', $voucherId)->delete();
    }
    public function getvoucherPurchase(int $id): array
    {
        $voucherPurchase = EloquentScVoucherdet::where('id_purchase', $id)->get();

        return  $voucherPurchase->map(function ($voucherPurchase) {
            return new ScVoucherdet(
                id: $voucherPurchase->id,
                cia: $voucherPurchase->cia,
                codcon: $voucherPurchase->codcon,
                tipdoc: $voucherPurchase->tipdoc,
                glosa: $voucherPurchase->glosa,
                impsol: $voucherPurchase->impsol,
                impdol: $voucherPurchase->impdol,
                id_purchase: $voucherPurchase->id_purchase,
                id_sc_voucher: $voucherPurchase->id_sc_voucher,
                numdoc: $voucherPurchase->numdoc,
                correlativo: $voucherPurchase->correlativo,
                serie: $voucherPurchase->serie,
            );
        })->toArray();
    }
    public function findDetailByVoucherAndPurchase(int $voucherId, int $purchaseId): ?ScVoucherdet
    {
        $scVoucherdet = EloquentScVoucherdet::where('id_sc_voucher', $voucherId)
            ->where('id_purchase', $purchaseId)
            ->first();

        if (!$scVoucherdet) {
            return null;
        }

        return new ScVoucherdet(
            id: $scVoucherdet->id,
            cia: $scVoucherdet->cia,
            codcon: $scVoucherdet->codcon,
            tipdoc: $scVoucherdet->tipdoc,
            glosa: $scVoucherdet->glosa,
            impsol: $scVoucherdet->impsol,
            impdol: $scVoucherdet->impdol,
            id_purchase: $scVoucherdet->id_purchase,
            id_sc_voucher: $scVoucherdet->id_sc_voucher,
            numdoc: $scVoucherdet->numdoc,
            correlativo: $scVoucherdet->correlativo,
            serie: $scVoucherdet->serie,
        );
    }
}
