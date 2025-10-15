<?php

namespace App\Modules\Bank\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Bank\Application\DTOs\BankDTO;
use App\Modules\Bank\Application\UseCases\CreateBankUseCase;
use App\Modules\Bank\Application\UseCases\FindAllBanksUseCase;
use App\Modules\Bank\Application\UseCases\FindByIdBankUseCase;
use App\Modules\Bank\Application\UseCases\UpdateBankUseCase;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\Bank\Infrastructure\Requests\StoreBankRequest;
use App\Modules\Bank\Infrastructure\Resources\BankResource;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BankController extends Controller
{
    public function __construct(
        private readonly BankRepositoryInterface $bankRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
    ){}

    public function index(): array
    {
        $bankUseCase = new FindAllBanksUseCase($this->bankRepository);
        $banks = $bankUseCase->execute();

        return BankResource::collection($banks)->resolve();
    }

    public function store(StoreBankRequest $request): JsonResponse
    {
         $bankDTO = new BankDTO($request->validated());
         $bankUseCase = new CreateBankUseCase($this->bankRepository, $this->currencyTypeRepository, $this->userRepository, $this->companyRepository);
         $bank = $bankUseCase->execute($bankDTO);

         return response()->json(new BankResource($bank), 201);
    }

    public function show($id): JsonResponse
    {
        $bankUseCase = new FindByIdBankUseCase($this->bankRepository);
        $bank = $bankUseCase->execute($id);

        return response()->json(new BankResource($bank), 200);
    }

    public function update(StoreBankRequest $request, $id): JsonResponse
    {
        $bankDTO = new BankDTO($request->validated());
        $bankUseCase = new UpdateBankUseCase($this->bankRepository, $this->currencyTypeRepository, $this->userRepository, $this->companyRepository);
        $bank = $bankUseCase->execute($id, $bankDTO);

        return response()->json(new BankResource($bank), 200);
    }
}
