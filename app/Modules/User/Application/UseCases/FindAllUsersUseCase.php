<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class FindAllUsersUseCase
{
    private userRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(?string $description, ?int $roleId, ?int $statusLogin, ?int $status)
    {
        return $this->userRepository->findAllUsers($description, $roleId, $statusLogin, $status);
    }
}
