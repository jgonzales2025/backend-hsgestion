<?php

namespace App\Modules\UserAssignment\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\UserAssignment\Application\UseCases\FindBranchesByUserUserCase;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;

class UserAssignmentController extends Controller
{
    public function __construct(private readonly UserAssignmentRepositoryInterface $userAssignmentRepository){}

    public function indexBranchesByUser(): array
    {
        $userId = request()->get('user_id');
        $companyId = request()->get('company_id');

        $userAssignmentUseCase = new FindBranchesByUserUserCase($this->userAssignmentRepository);
        return $userAssignmentUseCase->execute($userId, $companyId);
    }
}
