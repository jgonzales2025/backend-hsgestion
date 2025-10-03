<?php

namespace App\Modules\UserAssignment\Application\UseCases;

use App\Modules\UserAssignment\Application\DTOs\UserAssignmentDTO;
use App\Modules\UserAssignment\Domain\Entities\UserAssignment;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;

class CreateUserAssignmentUseCase
{
    private UserAssignmentRepositoryInterface $userAssignmentRepository;

    public function __construct(UserAssignmentRepositoryInterface $userAssignmentRepository)
    {
        $this->userAssignmentRepository = $userAssignmentRepository;
    }

    public function execute(UserAssignmentDTO $userAssignmentDTO): array
    {
        return $this->userAssignmentRepository->createUserAssignment(
            userId: $userAssignmentDTO->userId,
            assignments: $userAssignmentDTO->assignments,
            status: $userAssignmentDTO->status
        );

    }
}
