<?php
namespace App\Modules\Kardex\Domain\Interface;

use Modules\Kardex\Domain\Entites\Kardex;

interface KardexRepositoryInterface
{
    public function getAll(): array;
    public function getById(int $id): ?Kardex;
    public function save(Kardex $kardex): ?Kardex;
    public function update(Kardex $kardex): ?Kardex;
    public function getKardexByProductId(
        int $productId,
        int $companyId,
        int $branchId,
        string $fecha,
        string $fecha1,
        // int $categoria,
        // int $marca,
    ): array;
}
