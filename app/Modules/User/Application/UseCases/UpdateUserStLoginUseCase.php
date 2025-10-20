<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class UpdateUserStLoginUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository){}

    public function execute($id, $st_login): void
    {
        $this->userRepository->updateStLogin($id, $st_login);
    }
}
