<?php

namespace App\Modules\Bank\Application\UseCases;

use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;

readonly class FindAllBanksUseCase
{
    public function __construct(private readonly BankRepositoryInterface $bankRepository){}

    public function execute(?string $description, ?int $status, ?int $currency_type_id)
    {
        return $this->bankRepository->findAll($description, $status, $currency_type_id);
    }
}
