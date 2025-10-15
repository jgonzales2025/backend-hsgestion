<?php

namespace App\Modules\ExchangeRate\Application\UseCases;

use App\Modules\ExchangeRate\Application\DTOs\ExchangeRateDTO;
use App\Modules\ExchangeRate\Domain\Entities\ExchangeRate;
use App\Modules\ExchangeRate\Domain\Interfaces\ExchangeRateRepositoryInterface;

readonly class UpdateExchangeRateUseCase
{
    public function __construct(private readonly ExchangeRateRepositoryInterface $exchangeRateRepository){}

    public function execute(int $id, ExchangeRateDTO $exchangeRateDTO): ?ExchangeRate
    {
        $exchangeRate = new ExchangeRate(
            id: $id,
            date: null,
            purchase_rate: null,
            sale_rate: null,
            parallel_rate: $exchangeRateDTO->parallel_rate,
        );

        return $this->exchangeRateRepository->update($exchangeRate);
    }
}
