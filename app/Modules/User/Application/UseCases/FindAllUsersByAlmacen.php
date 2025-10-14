<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

class FindAllUsersByAlmacen
{
    private userRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(): array
    {
        return $this->userRepository->findAllUsersByAlmacen();
    }
}
