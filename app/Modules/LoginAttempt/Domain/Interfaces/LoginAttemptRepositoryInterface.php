<?php

namespace App\Modules\LoginAttempt\Domain\Interfaces;

use App\Modules\LoginAttempt\Domain\Entities\LoginAttempt;

interface LoginAttemptRepositoryInterface
{
    public function findAllLoginAttempts(): array;
    public function save(LoginAttempt $loginAttempt): void;
}
