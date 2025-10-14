<?php

namespace App\Modules\Ubigeo\Departments\Domain\Interfaces;

interface DepartmentRepositoryInterface
{
    public function findAll(): array;
}
