<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\CustomerPortfolio\Application\DTOs\UpdateCustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;

readonly class UpdateCustomerPorfolioUseCase
{
    public function __construct(private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository){}

    public function execute($id, UpdateCustomerPortfolioDTO $customerPortfolioDTO): void
    {
        $this->customerPortfolioRepository->updateCustomerPortfolio($id, $customerPortfolioDTO->userId);
    }
}
