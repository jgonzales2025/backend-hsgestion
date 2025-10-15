<?php

namespace App\Modules\CurrencyType\Application\UseCases;

use App\Modules\CurrencyType\Domain\Entities\CurrencyType;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;

readonly class FindByIdCurrencyTypeUseCase
{
    public function __construct(private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository){}

    public function execute(int $id): ?CurrencyType
    {
        return $this->currencyTypeRepository->findById($id);
    }
}
