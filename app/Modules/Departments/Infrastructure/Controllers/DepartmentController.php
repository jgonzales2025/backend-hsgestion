<?php

namespace App\Modules\Departments\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Departments\Application\UseCases\FindAllDepartmentsUseCase;
use App\Modules\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Departments\Infrastructure\Resources\DepartmentResource;

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
