<?php

namespace App\Modules\User\Domain\Interfaces;

use App\Modules\User\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): ?User;
    public function findById(int $id): ?User;
    public function update(User $user): void;
    public function delete(User $user): void;
    public function findAllUserName(): array;
    public function findAllUsers(): array;
    public function findAllUsersByVendedor(): array;
    public function findAllUsersByAlmacen(): array;
    public function findByUserName(string $userName): ?User;
}
