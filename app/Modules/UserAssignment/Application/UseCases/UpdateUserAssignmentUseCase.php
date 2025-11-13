<?php

namespace App\Modules\UserAssignment\Application\UseCases;

use App\Modules\UserAssignment\Application\DTOs\UserAssignmentDTO;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;

class UpdateUserAssignmentUseCase
{
    private UserAssignmentRepositoryInterface $userAssignmentRepository;

    public function __construct(UserAssignmentRepositoryInterface $userAssignmentRepository)
    {
        $this->userAssignmentRepository = $userAssignmentRepository;
    }

    public function execute(UserAssignmentDTO $userAssignmentDTO): array
    {
        return $this->userAssignmentRepository->updateUserAssignments(
            userId: $userAssignmentDTO->userId,
            assignments: $userAssignmentDTO->assignments
        );
    }

}
