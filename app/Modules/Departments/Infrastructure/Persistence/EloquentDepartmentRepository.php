<?php

namespace App\Modules\Departments\Infrastructure\Persistence;

use App\Modules\Departments\Domain\Entities\Department;
use App\Modules\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Departments\Infrastructure\Models\EloquentDepartment;

class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{

    public function findAll(): array
    {
        $departments = EloquentDepartment::all();

        return $departments->map(function ($department) {
            return new Department(
                coddep: $department->coddep,
                nomdep: $department->nomdep,
            );
        })->toArray();
    }
}
