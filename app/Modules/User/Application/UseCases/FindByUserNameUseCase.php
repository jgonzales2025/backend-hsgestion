<?php

namespace App\Modules\User\Application\UseCases;

use App\Modules\User\Domain\Entities\User;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class FindByUserNameUseCase
{
    public function __construct(private readonly UserRepositoryInterface $userRepository){}

    public function execute(string $userName): ?User
    {
        return $this->userRepository->findByUserName($userName);
    }
}
