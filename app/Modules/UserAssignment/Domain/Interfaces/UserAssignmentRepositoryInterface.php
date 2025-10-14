<?php

namespace App\Modules\UserAssignment\Domain\Interfaces;

use App\Modules\UserAssignment\Domain\Entities\UserAssignment;

interface UserAssignmentRepositoryInterface
{
    public function createUserAssignment(int $userId, array $assignments, int $status): ?array;
    public function updateUserAssignments(int $userId, array $assignments, int $status): array;
    public function deleteUserAssignments(int $userId): void;

}
