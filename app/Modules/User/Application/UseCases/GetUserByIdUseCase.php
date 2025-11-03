<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class GetUserByIdUseCase
{
    private userRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(?int $id): ?User
    {
        return $this->userRepository->findById($id);
    }
}
