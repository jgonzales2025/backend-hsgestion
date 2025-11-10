<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\PurchaseOrder\Application\DTOs\PurchaseOrderDTO;
use App\Modules\PurchaseOrder\Application\UseCases\CreatePurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\FindAllPurchaseOrdersUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\FindByIdPurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\UpdatePurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;
use App\Modules\PurchaseOrder\Infrastructure\Requests\StorePurchaseOrderRequest;
use App\Modules\PurchaseOrder\Infrastructure\Requests\UpdatePurchaseOrderRequest;
use App\Modules\PurchaseOrder\Infrastructure\Resources\PurchaseOrderResource;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGenerator,
    ){}

    public function index(): array
    {
        $role = request()->get('role');
        $branches = request()->get('branches');
        $companyId = request()->get('company_id');

        $purchaseOrderUseCase = new FindAllPurchaseOrdersUseCase($this->purchaseOrderRepository);
        $purchaseOrders = $purchaseOrderUseCase->execute($role, $branches, $companyId);

        return PurchaseOrderResource::collection($purchaseOrders)->resolve();
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $purchaseOrderDTO = new PurchaseOrderDTO($request->validated());
        $purchaseOrderUseCase = new CreatePurchaseOrderUseCase($this->purchaseOrderRepository, $this->customerRepository, $this->documentNumberGenerator);
        $purchaseOrder = $purchaseOrderUseCase->execute($purchaseOrderDTO);

        return response()->json(new PurchaseOrderResource($purchaseOrder), 201);
    }

    public function show(int $id): JsonResponse
    {
        $purchaseOrderUseCase = new FindByIdPurchaseOrderUseCase($this->purchaseOrderRepository);
        $purchaseOrder = $purchaseOrderUseCase->execute($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Orden de compra no encontrada'], 404);
        }

        return response()->json(new PurchaseOrderResource($purchaseOrder), 200);
    }

    public function update(UpdatePurchaseOrderRequest $request, int $id): JsonResponse
    {
        $purchaseOrderDTO = new PurchaseOrderDTO($request->validated());
        $purchaseOrderUseCase = new UpdatePurchaseOrderUseCase($this->purchaseOrderRepository, $this->customerRepository);
        $purchaseOrder = $purchaseOrderUseCase->execute($purchaseOrderDTO, $id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Orden de compra no encontrada'], 404);
        }

        return response()->json(new PurchaseOrderResource($purchaseOrder), 200);
    }
}
