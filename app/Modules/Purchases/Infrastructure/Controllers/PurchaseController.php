<?php

namespace App\Modules\Purchases\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DetailPurchaseGuides\Application\DTOS\DetailPurchaseGuideDTO;
use App\Modules\DetailPurchaseGuides\Application\UseCases\CreateDetailPurchaseGuideUseCase;
use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;
use App\Modules\DetailPurchaseGuides\Infrastructure\Resource\DetailPurchaseGuideResource;
use App\Modules\PaymentMethod\Domain\Interfaces\PaymentMethodRepositoryInterface;
use App\Modules\Purchases\Application\DTOS\PurchaseDTO;
use App\Modules\Purchases\Application\UseCases\CreatePurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindAllPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\FindByIdPurchaseUseCase;
use App\Modules\Purchases\Application\UseCases\UpdatePurchaseUseCase;
use App\Modules\Purchases\Domain\Interface\PurchaseRepositoryInterface;
use App\Modules\Purchases\Infrastructure\Request\CreatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Request\UpdatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use App\Modules\ShoppingIncomeGuide\Application\DTOS\ShoppingIncomeGuideDTO;
use App\Modules\ShoppingIncomeGuide\Application\UseCases\CreateShoppingIncomeGuideUseCase;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Resource\ShoppingIncomeGuideResource;
use Illuminate\Http\JsonResponse;
use App\Modules\Purchases\Application\UseCases\GeneratePurchasePdfUseCase;
use App\Modules\Purchases\Domain\Interface\GeneratepdfRepositoryInterface;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\Response;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepository,
        private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuideRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService
    ) {}

    public function index(): JsonResponse
    {
        $findAllPurchaseUseCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases = $findAllPurchaseUseCase->execute();

        $result = [];

        foreach ($purchases as $purchase) {
            $guide = $this->detailPurchaseGuideRepository->findById($purchase->getId());
            $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());

            $entryGuideIds = array_map(fn($item) => $item->getEntryGuideId(), $shopping);

            $result[] = array_merge(
                (new PurchaseResource($purchase))->resolve(),
                [
                    'details' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                    'entry_guide' => $entryGuideIds,
                ]
            );
        }

        return response()->json($result, 200);
    }

    public function show(int $id): JsonResponse
    {
        $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
        $purchase = $findByIdPurchaseUseCase->execute($id);
        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        }
        $guide = $this->detailPurchaseGuideRepository->findById($purchase->getId());
        $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());
        $entryGuideIds = array_map(fn($item) => $item->getEntryGuideId(), $shopping);
        return response()->json(
            array_merge(
                (new PurchaseResource($purchase))->resolve(),
                [
                    'det_compras_guia_ingreso' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                    'entry_guide' => $entryGuideIds,
                ]
            ),
            200
        );
    }
    public function store(CreatePurchaseRequest $request): JsonResponse
    {
        $purchaseDTO = new PurchaseDTO($request->validated());
        $cretaePurchaseUseCase = new CreatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentMethodRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
            $this->documentNumberGeneratorService,
        );
        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);
       
          $existingDetails = $this->detailPurchaseGuideRepository->findById($purchase->getId());

        $this->detailPurchaseGuideRepository->deletedBy($purchase->getId());

              $det_compras_guia_ingreso = $this->updateDetailCompra($purchase, $request->validated()['det_compras_guia_ingreso'], $existingDetails);
     

        $shopping_income_guide = $this->updateShopping($purchase,  $request->validated()['entry_guide']);

        $entryGuideIds = array_map(fn($item) => $item->getEntryGuideId(), $shopping_income_guide);

        return response()->json(
            array_merge(
                (new PurchaseResource($purchase))->resolve(),
                [
                    'det_compras_guia_ingreso' => DetailPurchaseGuideResource::collection($det_compras_guia_ingreso)->resolve(),
                   //purchaseGuide
                    'entry_guide' => $entryGuideIds,
                ]
            ),
            201
        );
    }
    public function update(UpdatePurchaseRequest $request, int $id): JsonResponse
    {

        $purchaseDTO = new PurchaseDTO($request->validated());
        $updatePurchaseUseCase = new UpdatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentMethodRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
        );
        $purchase = $updatePurchaseUseCase->execute($purchaseDTO, $id);

        // Get existing details BEFORE deleting to preserve original cantidad
        $existingDetails = $this->detailPurchaseGuideRepository->findById($purchase->getId());

        $this->detailPurchaseGuideRepository->deletedBy($purchase->getId());

        $this->shoppingIncomeGuideRepository->deletedBy($purchase->getId());


        $detailcompras = $this->updateDetailCompra($purchase, $request->validated()['det_compras_guia_ingreso'], $existingDetails);
        $shopping_income_guide = $this->updateShopping($purchase,  $request->validated()['entry_guide']);

        $entryGuideIds = array_map(fn($item) => $item->getEntryGuideId(), $shopping_income_guide);

        return response()->json(
            array_merge(
                (new PurchaseResource($purchase))->resolve(),
                [
                    'det_compras_guia_ingreso' => DetailPurchaseGuideResource::collection($detailcompras)->resolve(),
                    'entry_guide' => $entryGuideIds,
                ]
            ),
            201
        );
    }
    public function downloadPdf(int $id): Response
    {
        $useCase = new GeneratePurchasePdfUseCase(
            $this->purchaseRepository,
            $this->detailPurchaseGuideRepository,
            $this->shoppingIncomeGuideRepository,
            app(GeneratepdfRepositoryInterface::class)
        );
        return $useCase->execute($id);
    }

    public function createDetComprasGuiaIngreso($purchase, array $data)
    {
        $createGuideUseCase = new CreateDetailPurchaseGuideUseCase($this->detailPurchaseGuideRepository);

        return array_map(function ($item) use ($purchase, $createGuideUseCase) {

            // Crear relación purchase - entry_guide
            $detailDTO = new DetailPurchaseGuideDTO([
                'purchase_id' => $purchase->getId(),
                'article_id' => $item['article_id'],
                'description' => $item['description'],
                'precio_costo' => $item['precio_costo'],
                'descuento' => $item['descuento'],
                'sub_total' => $item['sub_total'],
                'total' => $item['total'],
                'cantidad_update' =>  $item['cantidad_update'],
                'process_status' => $item['process_status'],
            ]);

            $shoppingGuide = $createGuideUseCase->execute($detailDTO);

            return $shoppingGuide;
        }, $data);
    }
    public function createShoppingIncomeGuide($purchase,  $data)
    {
        $createGuideUseCase = new CreateShoppingIncomeGuideUseCase($this->shoppingIncomeGuideRepository);

        // Crear relación purchase - entry_guide
        $detailDTO = new ShoppingIncomeGuideDTO([
            'purchase_id' => $purchase->getId(),
            'entry_guide_id' => $data,
        ]);
        $shoppingGuide = $createGuideUseCase->execute($detailDTO);

        return $shoppingGuide;
    }

    private function updateShopping($shooping, array $data): array
    {
        $createShooping = new CreateShoppingIncomeGuideUseCase($this->shoppingIncomeGuideRepository);

        return array_map(function ($entryGuideId) use ($shooping, $createShooping) {
            $shoopingDTO = new ShoppingIncomeGuideDTO([
                'purchase_id' => $shooping->getId(),
                'entry_guide_id' => (int) $entryGuideId,    
            ]);

            $result = $createShooping->execute($shoopingDTO);
            return $result;
        }, $data);
    }
    private function updateDetailCompra($detail, array $data, array $existingDetails = []): array
    {
        $createDetail = new CreateDetailPurchaseGuideUseCase($this->detailPurchaseGuideRepository);

        return array_map(function ($purchase) use ($detail, $createDetail, $existingDetails) {

            // Find the original cantidad for this article from existing details
            $cantidadOriginal = $purchase['cantidad'] ?? 0;

            foreach ($existingDetails as $existingDetail) {
                if ($existingDetail->getArticleId() == $purchase['article_id']) {
                    $cantidadOriginal = $existingDetail->getCantidad();
                    break;
                }
            }

            // Calculate new cantidad: original - cantidad_update
            $cantidadUpdate = $purchase['cantidad_update'] ?? 0;
            $nuevaCantidad = $cantidadOriginal - $cantidadUpdate;

            // Ensure cantidad doesn't go negative
            if ($nuevaCantidad < 0) {
                $nuevaCantidad = 0;
            }

            $precio = isset($purchase['precio_costo']) ? (float) $purchase['precio_costo'] : 0.0;
            $descuento = isset($purchase['descuento']) ? (float) $purchase['descuento'] : 0.0;
            $subTotal = isset($purchase['sub_total']) ? (float) $purchase['sub_total'] : ($precio * (int) $nuevaCantidad);
            $total = isset($purchase['total']) ? (float) $purchase['total'] : ($subTotal - $descuento);

            $detailDto = new DetailPurchaseGuideDTO([
                'purchase_id' => $detail->getId(),
                'article_id' => $purchase['article_id'],
                'description' => $purchase['description'],
                'cantidad' => $nuevaCantidad,
                'precio_costo' => $precio,
                'descuento' => $descuento,
                'sub_total' => $subTotal,
                'total' => $total,
                'cantidad_update' => $cantidadUpdate,
                'process_status' => $nuevaCantidad == 0 ? 'Facturado' : 'En proceso',
            ]);

            $createDetail = $createDetail->execute($detailDto);

            return $createDetail;
        }, $data);
    }
    public function proovedor(int $id): JsonResponse
    {
        $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
        $purchase = $findByIdPurchaseUseCase->execute($id);
        if (!$purchase) {
            return response()->json(['message' => 'proovedor no encontrado'], 404);
        }
        $guide = $this->detailPurchaseGuideRepository->findById($purchase->getId());
        $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());

        return response()->json(
            [
                'purchase' => (new PurchaseResource($purchase))->resolve(),
                'details' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                'shopping' => ShoppingIncomeGuideResource::collection($shopping)->resolve()
            ],
            200
        );
    }

    /**
     * Update a purchase detail by decrementing cantidad based on cantidad_update
     *
     * @param int $id - The detail purchase guide ID
     * @return JsonResponse
     */
    public function updateDetail(int $id): JsonResponse
    {
        // Validate request
        $validated = request()->validate([
            'cantidad_update' => 'required|numeric|min:0',
        ], [
            'cantidad_update.required' => 'La cantidad a actualizar es requerida',
            'cantidad_update.numeric' => 'La cantidad a actualizar debe ser un número',
            'cantidad_update.min' => 'La cantidad a actualizar debe ser mayor o igual a 0',
        ]);

        $cantidadUpdate = $validated['cantidad_update'];

        // Find detail by ID
        $detail = $this->detailPurchaseGuideRepository->findByDetailId($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detalle de compra no encontrado'
            ], 404);
        }

        // Validate that cantidad_update does not exceed cantidad
        if ($cantidadUpdate > $detail->getCantidad()) {
            return response()->json([
                'success' => false,
                'message' => 'La cantidad a actualizar no puede ser mayor que la cantidad disponible',
                'cantidad_disponible' => $detail->getCantidad(),
                'cantidad_solicitada' => $cantidadUpdate
            ], 422);
        }

        // Calculate new cantidad
        $nuevaCantidad = $detail->getCantidad() - $cantidadUpdate;

        // Update entity
        $detail->setCantidad($nuevaCantidad);
        $detail->setCantidadUpdate($cantidadUpdate);

        // Save to database
        $updatedDetail = $this->detailPurchaseGuideRepository->save($detail);

        if (!$updatedDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el detalle de compra'
            ], 500);
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Detalle de compra actualizado exitosamente',
            'data' => (new DetailPurchaseGuideResource($updatedDetail))->resolve()
        ], 200);
    }
}
