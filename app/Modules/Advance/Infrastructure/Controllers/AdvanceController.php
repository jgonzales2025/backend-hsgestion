<?php

namespace App\Modules\Advance\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Advance\Application\DTOs\AdvanceDTO;
use App\Modules\Advance\Application\UseCases\CreateAdvanceUseCase;
use App\Modules\Advance\Application\UseCases\FindByCustomerIdUseCase;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Advance\Infrastructure\Requests\StoreAdvanceRequest;
use App\Modules\Advance\Infrastructure\Resources\AdvanceResource;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdvanceController extends Controller
{
    public function __construct(
        private AdvanceRepositoryInterface $advanceRepository,
        private CustomerRepositoryInterface $customerRepository,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
        private BankRepositoryInterface $bankRepository,
        private CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private DocumentNumberGeneratorService $documentNumberGeneratorService
        )
    {
    }

    public function store(StoreAdvanceRequest $request): JsonResponse
    {
        $advanceDTO = new AdvanceDTO($request->validated());
        $advanceUseCase = new CreateAdvanceUseCase($this->advanceRepository, $this->customerRepository, $this->paymentMethodRepository, $this->bankRepository, $this->currencyTypeRepository, $this->documentNumberGeneratorService);
        $advanceUseCase->execute($advanceDTO);

        return response()->json(['message' => 'Anticipo creado exitosamente.'], 201);
    }

    public function show($customerId): JsonResponse
    {
        $advanceUseCase = new FindByCustomerIdUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($customerId);

        if (!$advance) {
            return response()->json(['message' => 'Este cliente no tiene anticipos.'], 404);
        }

        return response()->json((new AdvanceResource($advance))->resolve());
    }
}