<?php

namespace App\Modules\UserAssignment\Infrastructure\Persistence;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\UserAssignment\Domain\Entities\UserAssignment;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;
use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;

class EloquentUserAssignmentRepository implements UserAssignmentRepositoryInterface
{

    public function createUserAssignment(int $userId, array $assignments, int $status): ?array
    {
        $userAssignment = [];

        foreach ($assignments as $assignment) {
            if ($assignment['branch_id'] == 0)
            {
                $branches = EloquentBranch::where('cia_id', $assignment['company_id'])->get();
                foreach ($branches as $branch) {
                    $eloquentAssignment = EloquentUserAssignment::create([
                        'user_id' => $userId,
                        'company_id' => $branch->cia_id,
                        'branch_id' => $branch->id,
                        'status' => $status,
                    ]);

                    $userAssignment[] = new UserAssignment(
                        id: $eloquentAssignment->id,
                        userId: $eloquentAssignment->user_id,
                        ciaId: $eloquentAssignment->company_id,
                        branchId: $eloquentAssignment->branch_id,
                        status: $eloquentAssignment->status
                    );
                }
            } else {
                $eloquentAssignment = EloquentUserAssignment::create([
                    'user_id' => $userId,
                    'company_id' => $assignment['company_id'],
                    'branch_id' => $assignment['branch_id'],
                    'status' => $status,
                ]);

                $userAssignment[] = new UserAssignment(
                    id: $eloquentAssignment->id,
                    userId: $eloquentAssignment->user_id,
                    ciaId: $eloquentAssignment->company_id,
                    branchId: $eloquentAssignment->branch_id,
                    status: $eloquentAssignment->status
                );
            }
        }

        return $userAssignment;
    }

    public function updateUserAssignments(int $userId, array $assignments, int $status): array
    {
        // Eliminar las asignaciones anteriores
        $this->deleteUserAssignments($userId);

        // Crear las nuevas asignaciones
        return $this->createUserAssignment($userId, $assignments, $status);
    }

    public function deleteUserAssignments(int $userId): void
    {
        EloquentUserAssignment::where('user_id', $userId)->delete();
    }

}
