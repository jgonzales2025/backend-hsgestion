<?php

namespace App\Modules\Ubigeo\Departments\Application\UseCases;

use App\Modules\Ubigeo\Departments\Domain\Entities\Department;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;

readonly class FindByIdDepartmentUseCase
{
    public function __construct(private readonly DepartmentRepositoryInterface $departmentRepository){}

    public function execute(int $id): ?Department
    {
        return $this->departmentRepository->findById($id);
    }
}
