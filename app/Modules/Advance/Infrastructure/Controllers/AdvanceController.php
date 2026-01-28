<?php

namespace App\Modules\Advance\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Advance\Application\DTOs\AdvanceDTO;
use App\Modules\Advance\Application\UseCases\CreateAdvanceUseCase;
use App\Modules\Advance\Application\UseCases\FindAllAdvancesUseCase;
use App\Modules\Advance\Application\UseCases\FindByCustomerIdUseCase;
use App\Modules\Advance\Application\UseCases\FindByIdAdvanceUseCase;
use App\Modules\Advance\Application\UseCases\ToInvalidateAdvanceUseCase;
use App\Modules\Advance\Application\UseCases\UpdateAdvanceUseCase;
use App\Modules\Advance\Domain\Entities\UpdateAdvance;
use App\Modules\Advance\Domain\Interfaces\AdvanceRepositoryInterface;
use App\Modules\Advance\Infrastructure\Requests\StoreAdvanceRequest;
use App\Modules\Advance\Infrastructure\Requests\UpdateAdvanceRequest;
use App\Modules\Advance\Infrastructure\Resources\AdvanceResource;
use App\Modules\Bank\Domain\Interfaces\BankRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\Request;
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

    public function showAdvancesByCustomer($customerId): array|JsonResponse
    {
        $advanceUseCase = new FindByCustomerIdUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($customerId);

        if (!$advance) {
            return response()->json(['message' => 'Este cliente no tiene anticipos.'], 404);
        }

        return AdvanceResource::collection($advance)->resolve();
    }

    public function index(Request $request)
    {
        $description = $request->query('description');
        $company_id = request()->get('company_id');

        $advanceUseCase = new FindAllAdvancesUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($description, $company_id);

        return new JsonResponse([
            'data' => AdvanceResource::collection($advance->getCollection())->resolve(),
            'current_page' => $advance->currentPage(),
            'per_page' => $advance->perPage(),
            'total' => $advance->total(),
            'last_page' => $advance->lastPage(),
            'next_page_url' => $advance->nextPageUrl(),
            'prev_page_url' => $advance->previousPageUrl(),
            'first_page_url' => $advance->url(1),
            'last_page_url' => $advance->url($advance->lastPage()),
        ]);
    }

    public function show($id): JsonResponse
    {
        $advanceUseCase = new FindByIdAdvanceUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($id);

        if (!$advance) {
            return response()->json(['message' => 'Anticipo no encontrado.'], 404);
        }

        return response()->json(new AdvanceResource($advance));
    }
    
    public function update(UpdateAdvanceRequest $request, $id)
    {
        $advanceUseCase = new FindByIdAdvanceUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($id);
        if (!$advance) {
            return response()->json(['message' => 'Anticipo no encontrado.'], 404);
        }

        if ($advance->getSaldo() < $advance->getAmount()) {
            return response()->json(['message' => 'No se puede anular un anticipo que ya ha sido utilizado.'], 400);
        }

        if ($advance->getStatus() == 0) {
            return response()->json(['message' => 'No se puede actualizar un anticipo anulado.'], 400);
        }
        $data = $request->validated();
        $advanceDTO = new AdvanceDTO($data);
        $advanceUpdate = new UpdateAdvanceUseCase($this->advanceRepository, $this->customerRepository, $this->paymentMethodRepository, $this->bankRepository, $this->currencyTypeRepository);
        $advanceUpdate->execute($advanceDTO, $id);
        
        return response()->json(['message' => 'Anticipo actualizado exitosamente.'], 200);
    }

    public function toInvalidateAdvance($id): JsonResponse
    {
        $advanceUseCase = new FindByIdAdvanceUseCase($this->advanceRepository);
        $advance = $advanceUseCase->execute($id);

        if (!$advance) {
            return response()->json(['message' => 'Anticipo no encontrado.'], 404);
        }

        if ($advance->getSaldo() < $advance->getAmount()) {
            return response()->json(['message' => 'No se puede anular un anticipo que ya ha sido utilizado.'], 400);
        }

        $toInvalidateUseCase = new ToInvalidateAdvanceUseCase($this->advanceRepository);
        $toInvalidateUseCase->execute($id);

        return response()->json(['message' => 'Anticipo anulado exitosamente.'], 200);
    }
}