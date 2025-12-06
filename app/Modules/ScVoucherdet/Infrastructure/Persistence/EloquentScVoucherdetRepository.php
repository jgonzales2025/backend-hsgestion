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
        ]);

        return new ScVoucherdet(
            id: $scVoucherdet->id,
            cia: $scVoucherdet->cia,
            codcon: $scVoucherdet->codcon,
            tipdoc: $scVoucherdet->tipdoc,

            glosa: $scVoucherdet->glosa,
            impsol: $scVoucherdet->impsol,
            impdol: $scVoucherdet->impdol,
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
        ]);

        return new ScVoucherdet(
            id: $scVoucherdetA->id,
            cia: $scVoucherdetA->cia,
            codcon: $scVoucherdetA->codcon,
            tipdoc: $scVoucherdetA->tipdoc,

            glosa: $scVoucherdetA->glosa,
            impsol: $scVoucherdetA->impsol,
            impdol: $scVoucherdetA->impdol,
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
            );
        })->toArray();
    }

    public function findByVoucherId(int $voucherId): array
    {
        $scVoucherdetCollection = EloquentScVoucherdet::where('tipdoc', $voucherId)->get();

        return $scVoucherdetCollection->map(function ($scVoucherdet) {
            return new ScVoucherdet(
                id: $scVoucherdet->id,
                cia: $scVoucherdet->cia,
                codcon: $scVoucherdet->codcon,
                tipdoc: $scVoucherdet->tipdoc,

                glosa: $scVoucherdet->glosa,
                impsol: $scVoucherdet->impsol,
                impdol: $scVoucherdet->impdol,
            );
        })->toArray();
    }

    public function deleteByVoucherId(int $voucherId): void
    {
        EloquentScVoucherdet::where('tipdoc', $voucherId)->delete();
    }
}
