<?php

namespace App\Modules\Ubigeo\Departments\Domain\Interfaces;

use App\Modules\Ubigeo\Departments\Domain\Entities\Department;

interface DepartmentRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Department;
}
