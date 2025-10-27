<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\User\Domain\Entities\User;

readonly class FindUserByCustomerIdUseCase
{
    public function __construct(private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository){}

    public function execute(int $customerId): null|User|array
    {
        return $this->customerPortfolioRepository->findUserByCustomerId($customerId);
    }
}
