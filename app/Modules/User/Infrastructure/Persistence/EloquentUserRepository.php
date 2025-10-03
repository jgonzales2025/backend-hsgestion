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
            'status' => $user->getStatus(),
        ]);

        return new User(
            id: $eloquentUser->id,
            username: $eloquentUser->username,
            firstname: $eloquentUser->firstname,
            lastname: $eloquentUser->lastname,
            password: $eloquentUser->password,
            status: $eloquentUser->status,
            role: $eloquentUser->getRoleId(),
        );
    }

    public function findById(int $id): ?User
    {
        $user = EloquentUser::with('roles')->find($id);

        if (!$user) {
            return null;
        }

        return new User(
            id: $user->id,
            username: $user->username,
            firstname: $user->firstname,
            lastname: $user->lastname,
            password: $user->password,
            status: $user->status,
            role: $user->roles->pluck('name'),
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
        $users = EloquentUser::with('roles')->get();

        if ($users->isEmpty()) {
            return [];
        }

        return $users->map(function ($user) {
            return new User(
                id: $user->id,
                username: $user->username,
                firstname: $user->firstname,
                lastname: $user->lastname,
                password: $user->password,
                status: $user->status,
                role: $user->roles->first()?->name,
            );
        })->toArray();
    }
}
