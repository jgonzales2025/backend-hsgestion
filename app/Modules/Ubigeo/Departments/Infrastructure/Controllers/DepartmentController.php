<?php

namespace App\Modules\Ubigeo\Departments\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ubigeo\Departments\Application\UseCases\FindAllDepartmentsUseCase;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Departments\Infrastructure\Resources\DepartmentResource;

class DepartmentController extends Controller
{
    public function __construct(private readonly DepartmentRepositoryInterface $departmentRepository){}

    public function index(): array
    {
        $departmentsUseCase = new FindAllDepartmentsUseCase($this->departmentRepository);
        $departments = $departmentsUseCase->execute();

        return DepartmentResource::collection($departments)->resolve();
    }
}
