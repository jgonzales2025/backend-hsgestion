<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UserDTO;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class CreateUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(UserDTO $userDTO): ?User
    {
        $user = new User(
            id: 0,
            username: $userDTO->username,
            firstname: $userDTO->firstname,
            lastname: $userDTO->lastname,
            password: $userDTO->password,
            status: $userDTO->status,
            role: $userDTO->role,
            assignments: null
        );

        return $this->userRepository->save($user);
    }
}
