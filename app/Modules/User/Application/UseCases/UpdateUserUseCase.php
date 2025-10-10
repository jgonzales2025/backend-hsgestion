<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Application\DTOs\UserDTO;
use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class UpdateUserUseCase
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }

    public function execute(int $id, UserDTO $userDTO): ?User
    {
        $existingUser = $this->userRepository->findById($id);

        if (!$existingUser) {
            return null;
        }

        $user = new User(
            id: $id,
            username: $userDTO->username,
            firstname: $userDTO->firstname,
            lastname: $userDTO->lastname,
            password: $userDTO->password ?? $existingUser->getPassword(),
            status: $userDTO->status,
            role: $userDTO->role,
            assignments: null
        );

        $this->userRepository->update($user);

        return $this->userRepository->findById($id);

    }
}
