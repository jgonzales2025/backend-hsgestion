<?php

namespace App\Modules\User\Infrastructure\Persistence;

use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Model\EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{

    public function save(User $user): ?User
    {
        $eloquentUser = EloquentUser::create([
            'username' => $user->getUsername(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'password' => $user->getPassword(),
            'status' => $user->getStatus()
        ]);

        return new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            role: null,
            assignments: null
        );
    }

    public function findById(int $id): ?User
    {
        $user = EloquentUser::with('roles', 'assignments')->find($id);

        if (!$user) {
            return null;
        }

        $assignments = $user->assignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'company_id' => $assignment->company_id,
                'company_name' => $assignment->company?->company_name,
                'branch_id' => $assignment->branch_id,
                'branch_name' => $assignment->branch?->name,
                'status' => ($assignment->status) == 1 ? 'Activo' : 'Inactivo',
            ];
        })->toArray();


        return new User(
            id: $user->id,
            username: $user->username,
            firstname: $user->firstname,
            lastname: $user->lastname,
            password: $user->password,
            status: $user->status,
            role: $user->roles->first()?->name,
            assignments: $assignments
        );
    }

    public function update(User $user): void
    {
        // TODO: Implement update() method.
    }

    public function delete(User $user): void
    {
        // TODO: Implement delete() method.
    }

    public function findAllUserName(): array
    {
        $users = EloquentUser::with('roles')->get();

        if ($users->isEmpty()) {
            return [];
        }

        return $users->pluck('username')->toArray();
    }

    public function findAllUsers(): array
    {
        $users = EloquentUser::with('roles', 'assignments')->get();

        if ($users->isEmpty()) {
            return [];
        }

        return $users->map(function ($user) {
            $assignments = $user->assignments->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'company_id' => $assignment->company_id,
                    'branch_id' => $assignment->branch_id,
                    'status' => $assignment->status,
                ];
            })->toArray();

            return new User(
                id: $user->id,
                username: $user->username,
                firstname: $user->firstname,
                lastname: $user->lastname,
                password: $user->password,
                status: $user->status,
                role: $user->roles->first()?->name,
                assignments: $assignments
            );
        })->toArray();
    }
}
