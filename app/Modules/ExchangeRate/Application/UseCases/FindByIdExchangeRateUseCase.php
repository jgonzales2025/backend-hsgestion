<?php

namespace App\Modules\ExchangeRate\Application\UseCases;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;

readonly class FindByIdExchangeRateUseCase
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function execute(int $id): ?ExchangeRate
    {
        return $this->exchangeRateRepository->findById($id);
    }
}
