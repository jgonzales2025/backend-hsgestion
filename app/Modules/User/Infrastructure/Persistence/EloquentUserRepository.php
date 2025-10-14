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
            'password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
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
        $eloquentUser = EloquentUser::find($user->getId());

        if (!$eloquentUser) {
            throw new \Exception("Usuario no encontrado");
        }

        $data = [
            'username' => $user->getUsername(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'status' => $user->getStatus(),
        ];

        if ($user->getPassword() !== null
            && $user->getPassword() !== $eloquentUser->password
            && !str_starts_with($user->getPassword(), '$2y$')) {
            $data['password'] = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        }

        $eloquentUser->update($data);

    }

    public function delete(User $user): void
    {
        // TODO: Implement delete() method.
    }

    public function findAllUserName(): array
    {
        $users = EloquentUser::with('roles')->where('status', 1)->get();

        if ($users->isEmpty()) {
            return [];
        }

        return $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username
            ];
        })->toArray();
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

    public function findAllUsersByVendedor(): array
    {
        $users = EloquentUser::whereHas('roles', function ($query) {
            $query->where('name', 'Vendedor');
        })->with('roles', 'assignments')->get();

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

    public function findAllUsersByAlmacen(): array
    {
        $users = EloquentUser::whereHas('roles', function ($query) {
            $query->where('name', 'Almacenero');
        })->with('roles', 'assignments')->get();

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
