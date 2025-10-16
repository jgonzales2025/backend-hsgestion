<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerPortfolio\Application\DTOs\CustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Application\UseCases\CreateCustomerPortfolioUseCase;
use App\Modules\CustomerPortfolio\Application\UseCases\FindAllCustomerPortfoliosUseCase;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Requests\StoreCustomerPortfolioRequest;
use App\Modules\CustomerPortfolio\Infrastructure\Resources\CustomerPortfolioResource;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CustomerPortfolioController extends Controller
{
    public function __construct(
        private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly UserRepositoryInterface $userRepository
    ){}

    public function index(): array
    {
        $customerPortfoliosUseCase = new FindAllCustomerPortfoliosUseCase($this->customerPortfolioRepository);
        $customerPortfolios = $customerPortfoliosUseCase->execute();

        return CustomerPortfolioResource::collection($customerPortfolios)->resolve();
    }

    public function store(StoreCustomerPortfolioRequest $request): array
    {
        $customerPortfolioDTO = new CustomerPortfolioDTO($request->validated());
        $customerPortfolioUseCase = new CreateCustomerPortfolioUseCase($this->customerPortfolioRepository, $this->customerRepository, $this->userRepository);
        $customerPortfolios = $customerPortfolioUseCase->execute($customerPortfolioDTO);

        return CustomerPortfolioResource::collection($customerPortfolios)->resolve();
    }
}
