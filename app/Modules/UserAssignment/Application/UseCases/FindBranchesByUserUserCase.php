<?php

namespace App\Modules\UserAssignment\Application\UseCases;

use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;

readonly class FindBranchesByUserUserCase
{
    public function __construct(private readonly UserAssignmentRepositoryInterface $userAssignmentRepository){}

    public function execute(int $userId, int $companyId, ?string $type): array
    {
        return $this->userAssignmentRepository->findBranchesByUser($userId, $companyId, $type);
    }
}
