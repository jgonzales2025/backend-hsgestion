<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;

readonly class FindAllCustomerPortfoliosUseCase
{
    public function __construct(private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository) {}

    public function execute(): array
    {
        return $this->customerPortfolioRepository->findAll();
    }
}
