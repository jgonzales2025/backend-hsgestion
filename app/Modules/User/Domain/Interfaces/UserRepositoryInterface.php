<?php

namespace App\Modules\User\Domain\Interfaces;

use App\Modules\User\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): ?User;
    public function findById(int $id): ?User;
    public function update(User $user): void;
    public function findAllUsers(): array;
    public function findByUserName(string $userName): ?User;
    public function updateStLogin(int $id, int $stLogin): void;
    public function findAllUsersByVendedor(): array;
    public function passwordValidation(string $password): bool|array;
    public function updateStatus(int $id, int $status): void;
}
