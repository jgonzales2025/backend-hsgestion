<?php

namespace App\Modules\Ubigeo\Departments\Application\UseCases;

use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;

readonly class FindAllDepartmentsUseCase
{
    public function __construct(private readonly DepartmentRepositoryInterface $departmentRepository){}

    public function execute(): array
    {
        return $this->departmentRepository->findAll();
    }
}
