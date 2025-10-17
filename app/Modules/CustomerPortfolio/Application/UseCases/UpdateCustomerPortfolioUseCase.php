<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\CustomerPortfolio\Application\DTOs\UpdateCustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;

readonly class UpdateCustomerPortfolioUseCase
{
    public function __construct(private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository){}

    public function execute(UpdateCustomerPortfolioDTO $customerPortfolioDTO): void
    {
        $this->customerPortfolioRepository->update($customerPortfolioDTO->id, $customerPortfolioDTO->newUserId);
    }
}
