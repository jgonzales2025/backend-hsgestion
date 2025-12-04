<?php

namespace App\Modules\LoginAttempt\Domain\Interfaces;

use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;

interface LoginAttemptRepositoryInterface
{
    public function findAllLoginAttempts(string $description, string $roleId);
    public function save(LoginAttempt $loginAttempt): void;
}
