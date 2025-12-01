<?php

namespace App\Modules\BuildDetailPc\Domain\Interface;

use App\Modules\BuildDetailPc\Domain\Entities\BuildDetailPc;

interface BuildDetailPcRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?BuildDetailPc;
    public function findByBuildPcId(int $buildPcId): array;
    public function create(BuildDetailPc $data): ?BuildDetailPc;
    public function update(BuildDetailPc $data): ?BuildDetailPc;
    public function deleteByBuildPcId(int $buildPcId): void;
}
