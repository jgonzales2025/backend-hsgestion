<?php

namespace App\Modules\Departments\Domain\Interfaces;

interface DepartmentRepositoryInterface
{
    public function findAll(): array;
}
