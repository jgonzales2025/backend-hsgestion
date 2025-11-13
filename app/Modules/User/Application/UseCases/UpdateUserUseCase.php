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

        $user = new User(
            id: $id,
            username: $existingUser->getUsername(),
            firstname: $userDTO->firstname,
            lastname: $userDTO->lastname,
            password: $userDTO->password ?? $existingUser->getPassword(),
            roles: $userDTO->userRoles,
            assignment: null,
            st_login: null,
            password_item: $userDTO->password_item ?? $existingUser->getPasswordItem()
        );

        $this->userRepository->update($user);

        return $this->userRepository->findById($id);

    }
}
