<?php

namespace App\Modules\BuildPc\Domain\Interface;

use App\Modules\BuildPc\Domain\Entities\BuildPc;

interface BuildPcRepositoryInterface
{
    public function create(BuildPc $data): ?BuildPc;
    public function findById(int $id): ?BuildPc;
    public function findAll(?string $search, ?int $is_active);
    public function update(BuildPc $data): ?BuildPc;
}
