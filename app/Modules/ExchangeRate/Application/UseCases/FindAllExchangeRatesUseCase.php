<?php

namespace App\Modules\ExchangeRate\Application\UseCases;

use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;

readonly class FindAllExchangeRatesUseCase
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function execute(): array
    {
        return $this->exchangeRateRepository->findAll();
    }
}
