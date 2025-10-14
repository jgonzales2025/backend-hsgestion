<?php

namespace App\Modules\ExchangeRate\Application\UseCases;

use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;

readonly class FindExchangeRateUseCase
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function execute(): ?ExchangeRate
    {
        return $this->exchangeRateRepository->find();
    }
}
