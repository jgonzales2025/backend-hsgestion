<?php

namespace App\Modules\Kardex\Infrastructure\Persistence;

use App\Modules\Kardex\Domain\Interface\KardexRepositoryInterface;
use App\Modules\Kardex\Infrastructure\Models\EloquentKardex;
use Illuminate\Support\Facades\DB;
use Modules\Kardex\Domain\Entites\Kardex as KardexEntites;

class EloquenKardexRepository implements KardexRepositoryInterface
{
    public function getAll(): array
    {
        $eloquentKardex = EloquentKardex::with(['company', 'branch'])->get();
        return $eloquentKardex->map(function ($item) {
            return new KardexEntites(
                id: $item->id,
                company: $item->company->toDomain($item->company),
                branch: $item->branch->toDomain($item->branch),
                codigo: $item->codigo,
                is_today: $item->is_today,
                description: $item->description,
                before_fech: $item->before_fech,
                after_fech: $item->after_fech,
                status: $item->status,
            );
        })->toArray();
    }

    public function getById(int $id): ?KardexEntites
    {
        $eloquentKardex = EloquentKardex::with(['company', 'branch'])->find($id);
        if (!$eloquentKardex) {
            return null;
        }
        return new KardexEntites(
            id: $eloquentKardex->id,
            company: $eloquentKardex->company->toDomain($eloquentKardex->company),
            branch: $eloquentKardex->branch->toDomain($eloquentKardex->branch),
            codigo: $eloquentKardex->codigo,
            is_today: (bool)$eloquentKardex->is_today,
            description: $eloquentKardex->description,
            before_fech: $eloquentKardex->before_fech,
            after_fech: $eloquentKardex->after_fech,
            status: (bool)$eloquentKardex->status,
        );
    }

    public function save(KardexEntites $kardex): ?KardexEntites
    {
        $eloquentKardex = EloquentKardex::create([
            'company_id' => $kardex->getCompany()->getId(),
            'branch_id' => $kardex->getBranch()->getId(),
            'codigo' => $kardex->getCodigo(),
            'is_today' => $kardex->getIsToday(),
            'description' => $kardex->getDescription(),
            'before_fech' => $kardex->getBeforeFech(),
            'after_fech' => $kardex->getAfterFech(),
            'status' => $kardex->getStatus(),
        ]);
        $eloquentKardex->load(['company', 'branch']);
        return new KardexEntites(
            id: $eloquentKardex->id,
            company: $eloquentKardex->company->toDomain($eloquentKardex->company),
            branch: $eloquentKardex->branch->toDomain($eloquentKardex->branch),
            codigo: $eloquentKardex->codigo,
            is_today: (bool)$eloquentKardex->is_today,
            description: $eloquentKardex->description,
            before_fech: $eloquentKardex->before_fech,
            after_fech: $eloquentKardex->after_fech,
            status: (bool)$eloquentKardex->status,
        );
    }

    public function update(KardexEntites $kardex): ?KardexEntites
    {
        $kardexModel = EloquentKardex::find($kardex->getId());
        if (!$kardexModel) {
            return null;
        }
        $kardexModel->update([
            'company_id' => $kardex->getCompany()->getId(),
            'branch_id' => $kardex->getBranch()->getId(),
            'codigo' => $kardex->getCodigo(),
            'is_today' => $kardex->getIsToday(),
            'description' => $kardex->getDescription(),
            'before_fech' => $kardex->getBeforeFech(),
            'after_fech' => $kardex->getAfterFech(),
            'status' => $kardex->getStatus(),
        ]);
        $kardexModel->load(['company', 'branch']);
        return new KardexEntites(
            id: $kardexModel->id,
            company: $kardexModel->company->toDomain($kardexModel->company),
            branch: $kardexModel->branch->toDomain($kardexModel->branch),
            codigo: $kardexModel->codigo,
            is_today: (bool)$kardexModel->is_today,
            description: $kardexModel->description,
            before_fech: $kardexModel->before_fech,
            after_fech: $kardexModel->after_fech,
            status: (bool)$kardexModel->status,
        );
    }

    public function getKardexByProductId(
        int $productId,
        int $companyId,
        int $branchId,
        string $fecha,
        string $fecha1,
        // int $categoria,
        // int $marca,
    ): array {
        $results = DB::select(
            'CALL sp_kardex_fisico(?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $companyId,   // p_cia
                $branchId,    // pcodsuc
                $productId,   // p_idproducto
                $fecha,       // p_fecha
                $fecha1,      // p_fecha1
                0,   // p_categoria
                0,       // p_marca
                1     // p_consulta
            ]
        );

        // Opcional: convertir resultados a array puro
        return json_decode(json_encode($results), true);
    }

}
