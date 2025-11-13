<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class UpdateStatusUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(int $id, int $status): void
    {
        $this->userRepository->updateStatus($id, $status);
    }
}
