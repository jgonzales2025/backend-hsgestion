<?php

namespace App\Modules\CustomerPortfolio\Application\UseCases;

use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerPortfolio\Application\DTOs\CustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Domain\Entities\CustomerPortfolio;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\User\Application\UseCases\GetUserByIdUseCase;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;

readonly class CreateCustomerPortfolioUseCase
{
    public function __construct(
        private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly UserRepositoryInterface $userRepository,
    ){}

    public function execute(CustomerPortfolioDTO $customerPortfolioDTO): array
    {
        $userUseCase = new GetUserByIdUseCase($this->userRepository);
        $user = $userUseCase->execute($customerPortfolioDTO->user_id);

        $customerPortfolios = [];

        foreach ($customerPortfolioDTO->customer_ids as $customerId) {
            $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
            $customer = $customerUseCase->execute($customerId);

            $customerPortfolio = new CustomerPortfolio(
                id: 0,
                customer: $customer,
                user: $user,
                created_at: null,
                updated_at: null,
            );

            $customerPortfolios[] = $this->customerPortfolioRepository->save($customerPortfolio);
        }

        return $customerPortfolios;
    }
}
