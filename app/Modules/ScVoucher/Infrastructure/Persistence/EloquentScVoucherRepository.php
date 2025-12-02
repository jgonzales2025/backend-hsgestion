<?php

namespace App\Modules\ScVoucher\Infrastructure\Persistence;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Modules\ScVoucher\Infrastructure\Models\EloquentScVoucher;

class EloquentScVoucherRepository implements ScVoucherRepositoryInterface
{
    public function findById(int $id): ?ScVoucher
    {
        $eloquentScVoucher = EloquentScVoucher::find($id);

        if (!$eloquentScVoucher) {
            return null;
        }

        return new ScVoucher(
            id: $eloquentScVoucher->id,
            cia: $eloquentScVoucher->cia,
            anopr: $eloquentScVoucher->anopr,
            correlativo: $eloquentScVoucher->correlativo,
            fecha: $eloquentScVoucher->fecha,
            codban: $eloquentScVoucher->codban,
            codigo: $eloquentScVoucher->codigo,
            nroope: $eloquentScVoucher->nroope,
            glosa: $eloquentScVoucher->glosa,
            orden: $eloquentScVoucher->orden,
            tipmon: $eloquentScVoucher->tipmon,
            tipcam: $eloquentScVoucher->tipcam,
            total: $eloquentScVoucher->total,
            medpag: $eloquentScVoucher->medpag,
            tipopago: $eloquentScVoucher->tipopago,
            status: $eloquentScVoucher->status,
            usradi: $eloquentScVoucher->usradi,
            fecadi: $eloquentScVoucher->fecadi,
            usrmod: $eloquentScVoucher->usrmod,

        );
    }

    public function findAll()
    {
        $eloquentScVouchers = EloquentScVoucher::orderByDesc('created_at')
            ->paginate(10);

        // Transform the items in the paginator
        $eloquentScVouchers->getCollection()->transform(function ($eloquentScVoucher) {
            return new ScVoucher(
                id: $eloquentScVoucher->id,
                cia: $eloquentScVoucher->cia,
                anopr: $eloquentScVoucher->anopr,
                correlativo: $eloquentScVoucher->correlativo,
                fecha: $eloquentScVoucher->fecha,
                codban: $eloquentScVoucher->codban,
                codigo: $eloquentScVoucher->codigo,
                nroope: $eloquentScVoucher->nroope,
                glosa: $eloquentScVoucher->glosa,
                orden: $eloquentScVoucher->orden,
                tipmon: $eloquentScVoucher->tipmon,
                tipcam: $eloquentScVoucher->tipcam,
                total: $eloquentScVoucher->total,
                medpag: $eloquentScVoucher->medpag,
                tipopago: $eloquentScVoucher->tipopago,
                status: $eloquentScVoucher->status,
                usradi: $eloquentScVoucher->usradi,
                fecadi: $eloquentScVoucher->fecadi,
                usrmod: $eloquentScVoucher->usrmod,

            );
        });

        return $eloquentScVouchers;
    }

    public function create(ScVoucher $scVoucher): ?ScVoucher
    {
        $eloquentScVoucher = EloquentScVoucher::create([
            'cia' => $scVoucher->getCia(),
            'anopr' => $scVoucher->getAnopr(),
            'correlativo' => $scVoucher->getCorrelativo(),
            'fecha' => $scVoucher->getFecha(),
            'codban' => $scVoucher->getCodban(),
            'codigo' => $scVoucher->getCodigo(),
            'nroope' => $scVoucher->getNroope(),
            'glosa' => $scVoucher->getGlosa(),
            'orden' => $scVoucher->getOrden(),
            'tipmon' => $scVoucher->getTipmon(),
            'tipcam' => $scVoucher->getTipcam(),
            'total' => $scVoucher->getTotal(),
            'medpag' => $scVoucher->getMedpag(),
            'tipopago' => $scVoucher->getTipopago(),
            'status' => $scVoucher->getStatus(),
            'usradi' => $scVoucher->getUsradi(),
            'fecadi' => $scVoucher->getFecadi(),
            'usrmod' => $scVoucher->getUsrmod(),

        ]);

        return new ScVoucher(
            id: $eloquentScVoucher->id,
            cia: $eloquentScVoucher->cia,
            anopr: $eloquentScVoucher->anopr,
            correlativo: $eloquentScVoucher->correlativo,
            fecha: $eloquentScVoucher->fecha,
            codban: $eloquentScVoucher->codban,
            codigo: $eloquentScVoucher->codigo,
            nroope: $eloquentScVoucher->nroope,
            glosa: $eloquentScVoucher->glosa,
            orden: $eloquentScVoucher->orden,
            tipmon: $eloquentScVoucher->tipmon,
            tipcam: $eloquentScVoucher->tipcam,
            total: $eloquentScVoucher->total,
            medpag: $eloquentScVoucher->medpag,
            tipopago: $eloquentScVoucher->tipopago,
            status: $eloquentScVoucher->status,
            usradi: $eloquentScVoucher->usradi,
            fecadi: $eloquentScVoucher->fecadi,
            usrmod: $eloquentScVoucher->usrmod,

        );
    }

    public function update(ScVoucher $scVoucher): ?ScVoucher
    {
        $eloquentScVoucher = EloquentScVoucher::find($scVoucher->getId());

        if (!$eloquentScVoucher) {
            return null;
        }

        $eloquentScVoucher->update([
            'cia' => $scVoucher->getCia(),
            'anopr' => $scVoucher->getAnopr(),
            'correlativo' => $scVoucher->getCorrelativo(),
            'fecha' => $scVoucher->getFecha(),
            'codban' => $scVoucher->getCodban(),
            'codigo' => $scVoucher->getCodigo(),
            'nroope' => $scVoucher->getNroope(),
            'glosa' => $scVoucher->getGlosa(),
            'orden' => $scVoucher->getOrden(),
            'tipmon' => $scVoucher->getTipmon(),
            'tipcam' => $scVoucher->getTipcam(),
            'total' => $scVoucher->getTotal(),
            'medpag' => $scVoucher->getMedpag(),
            'tipopago' => $scVoucher->getTipopago(),
            'status' => $scVoucher->getStatus(),
            'usradi' => $scVoucher->getUsradi(),
            'fecadi' => $scVoucher->getFecadi(),
            'usrmod' => $scVoucher->getUsrmod(),

        ]);

        return new ScVoucher(
            id: $eloquentScVoucher->id,
            cia: $eloquentScVoucher->cia,
            anopr: $eloquentScVoucher->anopr,
            correlativo: $eloquentScVoucher->correlativo,
            fecha: $eloquentScVoucher->fecha,
            codban: $eloquentScVoucher->codban,
            codigo: $eloquentScVoucher->codigo,
            nroope: $eloquentScVoucher->nroope,
            glosa: $eloquentScVoucher->glosa,
            orden: $eloquentScVoucher->orden,
            tipmon: $eloquentScVoucher->tipmon,
            tipcam: $eloquentScVoucher->tipcam,
            total: $eloquentScVoucher->total,
            medpag: $eloquentScVoucher->medpag,
            tipopago: $eloquentScVoucher->tipopago,
            status: $eloquentScVoucher->status,
            usradi: $eloquentScVoucher->usradi,
            fecadi: $eloquentScVoucher->fecadi,
            usrmod: $eloquentScVoucher->usrmod,

        );
    }
}
