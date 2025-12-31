<?php

namespace App\Modules\UserAssignment\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\UserAssignment\Application\UseCases\FindBranchesByUserUserCase;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;
use Illuminate\Http\Request;

class UserAssignmentController extends Controller
{
    public function __construct(private readonly UserAssignmentRepositoryInterface $userAssignmentRepository){}

    public function indexBranchesByUser(Request $request): array
    {
        $userId = request()->get('user_id');
        $companyId = request()->get('company_id');
        $type = $request->query('type');

        $userAssignmentUseCase = new FindBranchesByUserUserCase($this->userAssignmentRepository);
        return $userAssignmentUseCase->execute($userId, $companyId, $type);
    }
}
