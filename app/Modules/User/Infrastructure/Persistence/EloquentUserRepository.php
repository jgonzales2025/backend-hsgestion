<?php

namespace App\Modules\User\Infrastructure\Persistence;

use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Model\EloquentUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EloquentUserRepository implements UserRepositoryInterface
{

    public function save(User $user): ?User
    {
        $eloquentUser = EloquentUser::create([
            'username' => $user->getUsername(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'password' => password_hash($user->getPassword(), PASSWORD_BCRYPT),
            'status' => $user->getStatus(),
            'password_item' => Hash::make($user->getPasswordItem()),
        ]);

        return new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            roles: null,
            assignment: null,
            st_login: $eloquentUser->st_login,
            password_item: $eloquentUser->password_item
        );
    }

    public function findById(?int $id): ?User
    {
        $user = EloquentUser::with('roles', 'assignments')->find($id);

        if (!$user) {
            return null;
        }

        // Agrupar asignaciones por company_id
        $groupedByCompany = $user->assignments->groupBy('company_id');

        $assignments = $groupedByCompany->map(function ($companyAssignments, $companyId) {
            // Si hay más de una sucursal para esta compañía
            if ($companyAssignments->count() > 1) {
                $firstAssignment = $companyAssignments->first();
                return [
                    'id' => $firstAssignment->id,
                    'company_id' => $companyId,
                    'company_name' => $firstAssignment->company?->company_name,
                    'branch_id' => 0,
                    'branch_name' => 'Todas las sucursales',
                    'status' => ($firstAssignment->status) == 1 ? 'Activo' : 'Inactivo',
                ];
            }

            // Si solo hay una sucursal, devolver normalmente
            $assignment = $companyAssignments->first();
            return [
                'id' => $assignment->id,
                'company_id' => $assignment->company_id,
                'company_name' => $assignment->company?->company_name,
                'branch_id' => $assignment->branch_id,
                'branch_name' => $assignment->branch?->name,
                'status' => ($assignment->status) == 1 ? 'Activo' : 'Inactivo',
            ];
        })->values()->toArray();

        return new User(
            id: $user->id,
            username: $user->username,
            firstname: $user->firstname,
            lastname: $user->lastname,
            password: $user->password,
            status: $user->status,
            roles: $user->roles->toArray(),
            assignment: $assignments,
            st_login: $user->st_login
        );
    }

    public function update(User $user): void
    {
        $eloquentUser = EloquentUser::find($user->getId());

        $data = [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'status' => $user->getStatus(),
            'password_item' => $user->getPasswordItem()
        ];

        if ($user->getPassword() !== null
            && $user->getPassword() !== $eloquentUser->password
            && !str_starts_with($user->getPassword(), '$2y$')) {
            $data['password'] = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        }

        $eloquentUser->update($data);
    }

    public function findAllUsers(): array
    {
        $users = EloquentUser::with('roles', 'assignments')->orderBy('created_at', 'desc')->get();

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
                roles: $user->roles->toArray(),
                assignment: $assignments,
                st_login: $user->st_login
            );
        })->toArray();
    }

    public function findByUserName(string $userName): ?User
    {
        $eloquentUser = EloquentUser::with('roles', 'assignments.company')->where('username', $userName)->first();

        if (!$eloquentUser) {
            return null;
        }

        $assignments = $eloquentUser->assignments
            ->unique('company_id')
            ->sortBy('company_id')
            ->map(function ($assignment) {
                return [
                    'company_id' => $assignment->company_id,
                    'company_name' => $assignment->company?->company_name,
                ];
            })
            ->values()
            ->toArray();

        return new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            roles: $eloquentUser->roles->toArray(),
            assignment: $assignments,
            st_login: $eloquentUser->st_login
        );
    }

    public function updateStLogin(int $id, int $stLogin): void
    {
        EloquentUser::where('id', $id)->update(['st_login' => $stLogin]);
    }

    public function findAllUsersByVendedor(): array
    {
        $usersVendedor = EloquentUser::whereHas('roles', function ($query) {
            $query->where('name', 'Vendedor');
        })->get();

        return $usersVendedor->map(function ($user) {
            return new User(
                id: $user->id,
                username: $user->username,
                firstname: $user->firstname,
                lastname: $user->lastname,
                password: $user->password,
                status: $user->status,
                roles: $user->roles->toArray(),
                assignment: null,
                st_login: $user->st_login
            );
        })->toArray();
    }

    public function passwordValidation(string $password): bool|array
    {
        $users = EloquentUser::whereNotNull('password_item')->get();

        foreach ($users as $user) {
            if (Hash::check($password, $user->password_item)) {
                return [
                    'status' => true,
                    'user_id' => $user->id
                ];
            }
        }

        return false;
    }
}
