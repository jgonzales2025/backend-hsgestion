<?php

namespace App\Modules\UserAssignment\Infrastructure\Persistence;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\UserAssignment\Domain\Entities\UserAssignment;
use App\Modules\UserAssignment\Domain\Interfaces\UserAssignmentRepositoryInterface;
use App\Modules\UserAssignment\Infrastructure\Models\EloquentUserAssignment;

class EloquentUserAssignmentRepository implements UserAssignmentRepositoryInterface
{

    public function createUserAssignment(int $userId, array $assignments): ?array
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
                    ]);
                    $eloquentAssignment->refresh();

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
                ]);
                $eloquentAssignment->refresh();

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

    public function updateUserAssignments(int $userId, array $assignments): array
    {
        // Eliminar las asignaciones anteriores
        $this->deleteUserAssignments($userId);

        // Crear las nuevas asignaciones
        return $this->createUserAssignment($userId, $assignments);
    }

    public function deleteUserAssignments(int $userId): void
    {
        EloquentUserAssignment::where('user_id', $userId)->delete();
    }

    public function findBranchesByUser(int $userId, int $companyId, ?string $type): array
    {

        $branches = EloquentUserAssignment::with('branch')->where('company_id', $companyId)->where('user_id', $userId)->get();

        if ($type === 'sales') {
            $branches = $branches->filter(function ($branch) {
                return $branch->branch->st_sales === 1;
            });
        } elseif ($type === 'entry_guides') {
            $branches = $branches->filter(function ($branch) {
                return $branch->branch->st_entry_guide === 1;
            });
        } elseif ($type === 'petty_cash') {
            $branches = $branches->filter(function ($branch) {
                return $branch->branch->st_petty_cash === 1;
            });
        }

        return $branches->map(function ($branch) {
            return [
                'branch_id' => $branch->branch_id,
                'branch_name' => $branch->branch->name,
                'address' => $branch->branch->address,
                'st_sales' => $branch->branch->st_sales,
                'st_entry_guide' => $branch->branch->st_entry_guide,
                'st_petty_cash' => $branch->branch->st_petty_cash
            ];
        })->toArray();
    }

}
