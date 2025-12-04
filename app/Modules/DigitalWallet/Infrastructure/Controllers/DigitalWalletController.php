<?php

namespace App\Modules\DigitalWallet\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\DigitalWallet\Application\DTOs\DigitalWalletDTO;
use App\Modules\DigitalWallet\Application\UseCases\CreateDigitalWalletUseCase;
use App\Modules\DigitalWallet\Application\UseCases\FindAllDigitalWalletUseCase;
use App\Modules\DigitalWallet\Application\UseCases\FindByIdDigitalWalletUseCase;
use App\Modules\DigitalWallet\Application\UseCases\UpdateDigitalWalletUseCae;
use App\Modules\DigitalWallet\Application\UseCases\UpdateStatusDigitalWalletUseCase;
use App\Modules\DigitalWallet\Domain\Interfaces\DigitalWalletRepositoryInterface;
use App\Modules\DigitalWallet\Infrastructure\Requests\StoreDigitalWalletRequest;
use App\Modules\DigitalWallet\Infrastructure\Requests\UpdateDigitalWalletRequest;
use App\Modules\DigitalWallet\Infrastructure\Resources\DigitalWalletResource;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DigitalWalletController extends Controller
{

    public function __construct(
        private readonly DigitalWalletRepositoryInterface $digitalWalletRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
    ){}

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $digitalWalletUseCase = new FindAllDigitalWalletUseCase($this->digitalWalletRepository);
        $digitalWallets = $digitalWalletUseCase->execute($description, $status);

        return new JsonResponse([
            'data' => DigitalWalletResource::collection($digitalWallets)->resolve(),
            'current_page' => $digitalWallets->currentPage(),
            'per_page' => $digitalWallets->perPage(),
            'total' => $digitalWallets->total(),
            'last_page' => $digitalWallets->lastPage(),
            'next_page_url' => $digitalWallets->nextPageUrl(),
            'prev_page_url' => $digitalWallets->previousPageUrl(),
            'first_page_url' => $digitalWallets->url(1),
            'last_page_url' => $digitalWallets->url($digitalWallets->lastPage()),
        ]);
    }

    public function store(StoreDigitalWalletRequest $request): JsonResponse
    {
        $digitalWalletDTO = new DigitalWalletDTO($request->validated());
        $digitalWalletUseCase = new CreateDigitalWalletUseCase($this->digitalWalletRepository, $this->companyRepository, $this->userRepository);
        $digitalWallet = $digitalWalletUseCase->execute($digitalWalletDTO);

        return response()->json(new DigitalWalletResource($digitalWallet), 201);
    }

    public function show($id): JsonResponse
    {
        $digitalWalletUseCase = new FindByIdDigitalWalletUseCase($this->digitalWalletRepository);
        $digitalWallet = $digitalWalletUseCase->execute($id);

        return response()->json(new DigitalWalletResource($digitalWallet), 200);
    }

    public function update(UpdateDigitalWalletRequest $request, $id): JsonResponse
    {
        $digitalWalletDTO = new DigitalWalletDTO($request->validated());
        $digitalWalletUseCase = new UpdateDigitalWalletUseCae($this->digitalWalletRepository, $this->companyRepository, $this->userRepository);
        $digitalWallet = $digitalWalletUseCase->execute($id, $digitalWalletDTO);

        return response()->json(new DigitalWalletResource($digitalWallet), 200);
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);

        $status = $validatedData['status'];

        $digitalWalletUseCase = new UpdateStatusDigitalWalletUseCase($this->digitalWalletRepository);
        $digitalWalletUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente'], 200);
    }
}
