<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\CustomerPortfolio\Application\DTOs\UpdateAllCustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;

readonly class UpdateAllCustomersByVendedorUseCase
{
    public function __construct(private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository){}

    public function execute(UpdateAllCustomerPortfolioDTO $customerPortfolioDTO): void
    {
        $this->customerPortfolioRepository->updateAllCustomersByVendedor($customerPortfolioDTO->userId, $customerPortfolioDTO->newUserId);
    }
}
