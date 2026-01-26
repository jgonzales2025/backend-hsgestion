<?php

namespace App\Modules\CustomerPortfolio\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\CustomerPortfolio\Application\DTOs\CustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Application\DTOs\UpdateAllCustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Application\UseCases\CreateCustomerPortfolioUseCase;
use App\Modules\CustomerPortfolio\Application\UseCases\FindAllCustomerPortfoliosUseCase;
use App\Modules\CustomerPortfolio\Application\UseCases\FindUserByCustomerIdUseCase;
use App\Modules\CustomerPortfolio\Application\UseCases\UpdateAllCustomersByVendedorUseCase;
use App\Modules\CustomerPortfolio\Application\UseCases\UpdateCustomerPorfolioUseCase;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\CustomerPortfolio\Infrastructure\Requests\StoreCustomerPortfolioRequest;
use App\Modules\CustomerPortfolio\Infrastructure\Requests\UpdateAllRequest;
use App\Modules\CustomerPortfolio\Infrastructure\Resources\CustomerPortfolioResource;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerPortfolioController extends Controller
{
    public function __construct(
        private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly UserRepositoryInterface $userRepository,
    ){}

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');

        $customerPortfoliosUseCase = new FindAllCustomerPortfoliosUseCase($this->customerPortfolioRepository);
        $customerPortfolios = $customerPortfoliosUseCase->execute($description);

        return new JsonResponse([
            'data' => CustomerPortfolioResource::collection($customerPortfolios)->resolve(),
            'current_page' => $customerPortfolios->currentPage(),
            'per_page' => $customerPortfolios->perPage(),
            'total' => $customerPortfolios->total(),
            'last_page' => $customerPortfolios->lastPage(),
            'next_page_url' => $customerPortfolios->nextPageUrl(),
            'prev_page_url' => $customerPortfolios->previousPageUrl(),
            'first_page_url' => $customerPortfolios->url(1),
            'last_page_url' => $customerPortfolios->url($customerPortfolios->lastPage()),
        ]);
    }

    public function store(StoreCustomerPortfolioRequest $request): array
    {
        $customerPortfolioDTO = new CustomerPortfolioDTO($request->validated());
        $customerPortfolioUseCase = new CreateCustomerPortfolioUseCase($this->customerPortfolioRepository, $this->customerRepository, $this->userRepository);
        $customerPortfolios = $customerPortfolioUseCase->execute($customerPortfolioDTO);

        return CustomerPortfolioResource::collection($customerPortfolios)->resolve();
    }

    public function updateAllCustomersByVendedor(UpdateAllRequest $request): JsonResponse
    {
        $customerPortfolioDTO = new UpdateAllCustomerPortfolioDTO($request->validated());
        $customerPortfolioUseCase = new UpdateAllCustomersByVendedorUseCase($this->customerPortfolioRepository);
        $customerPortfolioUseCase->execute($customerPortfolioDTO);

        return response()->json(['message' => 'Actualización de vendedor realizada con éxito'], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user_id = $request->input('user_sale_id');
        $customerPortfolioUseCase = new UpdateCustomerPorfolioUseCase($this->customerPortfolioRepository);
        $customerPortfolioUseCase->execute($id, $user_id);

        return response()->json(['message' => 'Registro actualizado con éxito'], 200);
    }

    public function showUserByCustomer($id): JsonResponse|array
    {
        $customerPortfolioUseCase = new FindUserByCustomerIdUseCase($this->customerPortfolioRepository);
        $user = $customerPortfolioUseCase->execute($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        if (is_array($user)) {
            return UserResource::collection($user)->resolve();
        } else {
            return response()->json((new UserResource($user))->resolve());
        }
    }
}
