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

    public function index(): array
    {
        $digitalWalletUseCase = new FindAllDigitalWalletUseCase($this->digitalWalletRepository);
        $digitalWallets = $digitalWalletUseCase->execute();

        return DigitalWalletResource::collection($digitalWallets)->resolve();
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
