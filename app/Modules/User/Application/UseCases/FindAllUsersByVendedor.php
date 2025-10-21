<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class FindAllUsersByVendedor
{
    public function __construct(private readonly UserRepositoryInterface $userRepository){}

    public function execute(): array
    {
        return $this->userRepository->findAllUsersByVendedor();
    }
}
