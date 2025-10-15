<?php

namespace App\Modules\Bank\Application\UseCases;

use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;

readonly class FindAllBanksUseCase
{
    public function __construct(private readonly BankRepositoryInterface $bankRepository){}

    public function execute(): array
    {
        return $this->bankRepository->findAll();
    }
}
