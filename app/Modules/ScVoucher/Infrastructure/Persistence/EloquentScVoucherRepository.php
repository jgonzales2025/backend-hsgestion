<?php

namespace App\Modules\ScVoucher\Infrastructure\Persistence;

use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Modules\ScVoucher\Infrastructure\Models\EloquentScVoucher;
use Illuminate\Support\Facades\DB;

class EloquentScVoucherRepository implements ScVoucherRepositoryInterface
{
    public function getLastDocumentNumber(string $serie): ?string
    {
        $entryGuide = EloquentScVoucher::where('nroope', $serie)
            ->orderBy('correlativO', 'desc')
            ->first();

        return $entryGuide?->correlativo;
    }


    public function findById(int $id): ?ScVoucher
    {
        $eloquentScVoucher = EloquentScVoucher::with(['customer', 'currencyType', 'paymentMethodSunat', 'paymentType'])->find($id);

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
            codigo: $eloquentScVoucher->customer?->toDomain($eloquentScVoucher->customer),
            nroope: $eloquentScVoucher->nroope,
            glosa: $eloquentScVoucher->glosa,
            orden: $eloquentScVoucher->orden,
            tipmon: $eloquentScVoucher->currencyType?->toDomain($eloquentScVoucher->currencyType),
            tipcam: $eloquentScVoucher->tipcam,
            total: $eloquentScVoucher->total,
            medpag: $eloquentScVoucher->paymentMethodSunat?->toDomain($eloquentScVoucher->paymentMethodSunat),
            tipopago: $eloquentScVoucher->paymentType?->toDomain($eloquentScVoucher->paymentType),
            status: $eloquentScVoucher->status,
            usradi: $eloquentScVoucher->usradi,
            fecadi: $eloquentScVoucher->fecadi,
            usrmod: $eloquentScVoucher->usrmod,

        );
    }

    public function findAll(?string $search)
    {
        $query = EloquentScVoucher::with(['customer', 'currencyType', 'paymentMethodSunat', 'paymentType', 'bank'])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('glosa', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $eloquentScVouchers = $query->paginate(10);

        // Transform the items in the paginator
        $eloquentScVouchers->getCollection()->transform(function ($eloquentScVoucher) {
            return new ScVoucher(
                id: $eloquentScVoucher->id,
                cia: $eloquentScVoucher->cia,
                anopr: $eloquentScVoucher->anopr,
                correlativo: $eloquentScVoucher->correlativo,
                fecha: $eloquentScVoucher->fecha,
                codban: $eloquentScVoucher->bank?->toDomain($eloquentScVoucher->bank),
                codigo: $eloquentScVoucher->customer?->toDomain($eloquentScVoucher->customer),
                nroope: $eloquentScVoucher->nroope,
                glosa: $eloquentScVoucher->glosa,
                orden: $eloquentScVoucher->orden,
                tipmon: $eloquentScVoucher->currencyType?->toDomain($eloquentScVoucher->currencyType),
                tipcam: $eloquentScVoucher->tipcam,
                total: $eloquentScVoucher->total,
                medpag: $eloquentScVoucher->paymentMethodSunat?->toDomain($eloquentScVoucher->paymentMethodSunat),
                tipopago: $eloquentScVoucher->paymentType?->toDomain($eloquentScVoucher->paymentType),
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
        DB::beginTransaction();

        try {
            $eloquentScVoucher = EloquentScVoucher::create([
                'cia' => $scVoucher->getCia(),
                'anopr' => $scVoucher->getAnopr() . "-" . date('n'),
                'correlativo' => $scVoucher->getCorrelativo(),
                'fecha' => $scVoucher->getFecha(),
                'codban' => $scVoucher->getCodban()?->getId(),
                'codigo' => $scVoucher->getCodigo()?->getId(),
                'nroope' => $scVoucher->getNroope(),
                'glosa' => $scVoucher->getGlosa(),
                'orden' => $scVoucher->getOrden(),
                'tipmon' => $scVoucher->getTipmon()?->getId(),
                'tipcam' => $scVoucher->getTipcam(),
                'total' => $scVoucher->getTotal(),
                'medpag' => $scVoucher->getMedpag()?->getCod(),
                'tipopago' => $scVoucher->getTipopago()?->getId(),
                'status' => $scVoucher->getStatus(),
                'usradi' => $scVoucher->getUsradi(),
                'fecadi' => $scVoucher->getFecadi(),
                'usrmod' => $scVoucher->getUsrmod(),

            ]);
            DB::statement("CALL update_purchase_balance(?, ?, ?, ?, ?)", [
                $scVoucher->getCia(),
                $scVoucher->getAnopr(),
                $scVoucher->getCorrelativo(),
                $scVoucher->getFecha(),
                $scVoucher->getTotal(),
            ]);

            DB::commit();

            return new ScVoucher(
                id: $eloquentScVoucher->id,
                cia: $eloquentScVoucher->cia,
                anopr: $eloquentScVoucher->anopr,
                correlativo: $eloquentScVoucher->correlativo,
                fecha: $eloquentScVoucher->fecha,
                codban: $eloquentScVoucher->bank?->toDomain($eloquentScVoucher->bank),
                codigo: $eloquentScVoucher->customer?->toDomain($eloquentScVoucher->customer),
                nroope: $eloquentScVoucher->nroope,
                glosa: $eloquentScVoucher->glosa,
                orden: $eloquentScVoucher->orden,
                tipmon: $eloquentScVoucher->currencyType?->toDomain($eloquentScVoucher->currencyType),
                tipcam: $eloquentScVoucher->tipcam,
                total: $eloquentScVoucher->total,
                medpag: $eloquentScVoucher->paymentMethodSunat?->toDomain($eloquentScVoucher->paymentMethodSunat),
                tipopago: $eloquentScVoucher->paymentType?->toDomain($eloquentScVoucher->paymentType),
                status: $eloquentScVoucher->status,
                usradi: $eloquentScVoucher->usradi,
                fecadi: $eloquentScVoucher->fecadi,
                usrmod: $eloquentScVoucher->usrmod,

            );
        } catch (\Throwable $th) {

            DB::rollBack();
            throw $th;
        }
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
            'fecha' => $scVoucher->getFecha(),
            'codban' => $scVoucher->getCodban()->getId(),
            'codigo' => $scVoucher->getCodigo()?->getId(),
            'nroope' => $scVoucher->getNroope(),
            'glosa' => $scVoucher->getGlosa(),
            'orden' => $scVoucher->getOrden(),
            'tipmon' => $scVoucher->getTipmon()?->getId(),
            'tipcam' => $scVoucher->getTipcam(),
            'total' => $scVoucher->getTotal(),
            'medpag' => $scVoucher->getMedpag()?->getCod(),
            'tipopago' => $scVoucher->getTipopago()?->getId(),
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
            codban: $eloquentScVoucher->bank?->toDomain($eloquentScVoucher->bank),
            codigo: $eloquentScVoucher->customer?->toDomain($eloquentScVoucher->customer),
            nroope: $eloquentScVoucher->nroope,
            glosa: $eloquentScVoucher->glosa,
            orden: $eloquentScVoucher->orden,
            tipmon: $eloquentScVoucher->currencyType?->toDomain($eloquentScVoucher->currencyType),
            tipcam: $eloquentScVoucher->tipcam,
            total: $eloquentScVoucher->total,
            medpag: $eloquentScVoucher->paymentMethodSunat?->toDomain($eloquentScVoucher->paymentMethodSunat),
            tipopago: $eloquentScVoucher->paymentType?->toDomain($eloquentScVoucher->paymentType),
            status: $eloquentScVoucher->status,
            usradi: $eloquentScVoucher->usradi,
            fecadi: $eloquentScVoucher->fecadi,
            usrmod: $eloquentScVoucher->usrmod,

        );
    }
    public function updateStatus(int $id, int $status)
    {
        $scVoucher = $this->findById($id);

        if (!$scVoucher) {
            return null;
        }
        $updatestatuseloquent = EloquentScVoucher::find($id);
        $updatestatuseloquent->update([
            'status' => $status,
        ]);

        return $updatestatuseloquent;
    }
}
