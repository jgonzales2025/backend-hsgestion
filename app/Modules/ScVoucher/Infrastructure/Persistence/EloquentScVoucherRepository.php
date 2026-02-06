<?php

namespace App\Modules\ScVoucher\Infrastructure\Persistence;

use App\Modules\DetVoucherPurchase\Infrastructure\Models\EloquentDetVoucherPurchase;
use App\Modules\ScVoucher\Domain\Entities\ScVoucher;
use App\Modules\ScVoucher\Domain\Interface\ScVoucherRepositoryInterface;
use App\Modules\ScVoucher\Infrastructure\Models\EloquentScVoucher;
use App\Modules\ScVoucherdet\Infrastructure\Models\EloquentScVoucherdet;
use Illuminate\Support\Facades\DB;

class EloquentScVoucherRepository implements ScVoucherRepositoryInterface
{
    public function getLastDocumentNumber(string $serie): ?string
    {
        $entryGuide = EloquentScVoucher::where('nroope', $serie)
            ->orderBy('correlativo', 'desc')
            ->first();

        return $entryGuide?->correlativo;
    }


    public function findById(int $id): ?ScVoucher
    {
        $eloquentScVoucher = EloquentScVoucher::with([
            'customer',
            'currencyType',
            'paymentMethodSunat',
            'paymentType',
            'bank',
            'details',
            'detailVoucherPurchase',
        ])->find($id);

        if (!$eloquentScVoucher) {
            return null;
        }

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
            path_image: $eloquentScVoucher->path_image,
            details: $eloquentScVoucher->details->map(fn($detail) => $detail->toDomain())->all(),
            detailVoucherpurchase: $eloquentScVoucher->detailVoucherPurchase->map(fn($detail) => $detail->toDomain())->all(),
        );
    }

    public function findAll(?string $search, ?int $status, ?string $fecha_inicio, ?string $fecha_fin)
    {
        $query = EloquentScVoucher::with([
            'customer',
            'currencyType',
            'paymentMethodSunat',
            'paymentType',
            'bank',
            'details',
            'detailVoucherPurchase'
        ])
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('glosa', 'like', "%{$search}%");
            });
        }

        if ($status !== null) {
            $query->where('status', $status);
        }
           if ($fecha_inicio !== null) {
            $query->where('fecha', '>=', $fecha_inicio);
        }
           if ($fecha_fin !== null) {
            $query->where('fecha', '<=', $fecha_fin);
        }


        $eloquentScVouchers = $query->paginate(10);

        // Transform the items in the paginator
        $eloquentScVouchers->getCollection()->transform(function ($eloquentScVoucher) {
            return $eloquentScVoucher->toDomain();
        });

        return $eloquentScVouchers;
    }

    public function create(ScVoucher $scVoucher): ?ScVoucher
    {
        return DB::transaction(function () use ($scVoucher) {

            // 🔐 1. OBTENER CORRELATIVO SEGURO (CIA + ANOPR)
            $ultimoCorrelativo = EloquentScVoucher::where('cia', $scVoucher->getCia())
                ->where('anopr', $scVoucher->getAnopr())
                ->lockForUpdate()
                ->max('correlativo');

            $correlativo = ($ultimoCorrelativo ?? 0) + 1;

            $eloquentScVoucher = EloquentScVoucher::create([
                'cia' => $scVoucher->getCia(),
                'anopr' => $scVoucher->getAnopr(), //. "-" . date('m')
                'correlativo' => $correlativo,
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
                'path_image' => $scVoucher->getPathImage(),
            ]);
            foreach ($scVoucher->getDetails() as $detailDTO) {
                EloquentScVoucherdet::create([
                    'id_sc_voucher' => $eloquentScVoucher->id,
                    'cia' => $eloquentScVoucher->cia,
                    'codcon' => $detailDTO->codcon,
                    'glosa' => $detailDTO->glosa,
                    'impsol' => number_format((float) $detailDTO->impsol, 4, '.', ''),
                    'impdol' => number_format((float) $detailDTO->impdol, 4, '.', ''),
                    'tipdoc' => $detailDTO->tipdoc,
                    'correlativo' => $detailDTO->correlativo,
                    'id_purchase' => $detailDTO->id_purchase,
                    'serie' => $detailDTO->serie,

                ]);

                DB::statement("CALL update_purchase_balance(?, ?, ?, ?, ?)", [
                    $eloquentScVoucher->cia,
                    $eloquentScVoucher->codigo,
                    $detailDTO->tipdoc,
                    $detailDTO->serie,
                    $detailDTO->correlativo,
                ]);
            }
            foreach ($scVoucher->getDetailVoucherpurchase() as $purchaseDTO) {
                EloquentDetVoucherPurchase::create([
                    'voucher_id' => $eloquentScVoucher->id,
                    'purchase_id' => $purchaseDTO->purchase_id,
                    'amount' => $purchaseDTO->amount,
                ]);
            }

            return $this->findWithRelations($eloquentScVoucher->id);
        });
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
            'path_image' => $scVoucher->getPathImage(),
        ]);

        EloquentScVoucherdet::where('id_sc_voucher', $scVoucher->getId())->delete();
        foreach ($scVoucher->getDetails() as $detailDTO) {
            EloquentScVoucherdet::create([
                'id_sc_voucher' => $eloquentScVoucher->id,
                'cia' => $eloquentScVoucher->cia,
                'codcon' => $detailDTO->codcon,
                'glosa' => $detailDTO->glosa,
                'impsol' => $detailDTO->impsol,
                'impdol' => $detailDTO->impdol,
                'tipdoc' => $detailDTO->tipdoc,
                'correlativo' => $detailDTO->correlativo,
                'id_purchase' => $detailDTO->id_purchase,
                'serie' => $detailDTO->serie,
            ]);

            DB::statement("CALL update_purchase_balance(?, ?, ?, ?, ?)", [
                $eloquentScVoucher->cia,
                $eloquentScVoucher->codigo,
                $detailDTO->tipdoc,
                $detailDTO->serie,
                $detailDTO->correlativo,
            ]);
        }
        EloquentDetVoucherPurchase::where('voucher_id', $scVoucher->getId())->delete();
        foreach ($scVoucher->getDetailVoucherpurchase() as $purchaseDTO) {
            EloquentDetVoucherPurchase::create([
                'voucher_id' => $eloquentScVoucher->id,
                'purchase_id' => $purchaseDTO->purchase_id,
                'amount' => $purchaseDTO->amount,
            ]);
        }

        return $this->findWithRelations($eloquentScVoucher->id);
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

        DB::statement("CALL sp_anula_voucher(?, ?)", [
            $scVoucher->getCia(),
            $id,
        ]);

        return $this->findById($id);
    }
    public function findWithRelations(int $id): ?ScVoucher
    {
        $model = EloquentScVoucher::with([
            'details',
            'detailVoucherPurchase',
            'bank',
            'customer',
            'currencyType',
            'paymentMethodSunat',
            'paymentType'
        ])->find($id);

        return $model?->toDomain();
    }

    public function updateImagePath(int $id, string $path): void
    {
        EloquentScVoucher::where('id', $id)->update(['path_image' => $path]);
    }
    public function getImagePath(int $id): ?string
    {
        return EloquentScVoucher::find($id)?->path_image;
    }
}
