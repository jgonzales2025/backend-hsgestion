<?php

namespace App\Modules\ExchangeRate\Application\UseCases;

use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;

class UpdateAlmacenUseCase
{
    public function __construct(private ExchangeRateRepositoryInterface $exchangeRateRepository)
    {
    }

    public function execute(int $id, bool $status): void
    {
        $this->exchangeRateRepository->updateAlmacen($id, $status);
    }
}