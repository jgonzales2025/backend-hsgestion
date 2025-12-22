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
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface; 
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
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
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB; 

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepository,
        private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuideRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGeneratorService,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository, 
    ) {}

    public function index(Request $request): JsonResponse
    {
        $description = $request->query('description');
        $num_doc = $request->query('num_doc');
        $id_proveedr = $request->query('id_proveedr');


        $findAllPurchaseUseCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases = $findAllPurchaseUseCase->execute($description, $num_doc, $id_proveedr);

        $result = PurchaseResource::collection($purchases)->resolve();

        return new JsonResponse([
            'data' => $result,
            'current_page' => $purchases->currentPage(),
            'per_page' => $purchases->perPage(),
            'total' => $purchases->total(),
            'last_page' => $purchases->lastPage(),
            'next_page_url' => $purchases->nextPageUrl(),
            'prev_page_url' => $purchases->previousPageUrl(),
            'first_page_url' => $purchases->url(1),
            'last_page_url' => $purchases->url($purchases->lastPage())
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
        $purchase = $findByIdPurchaseUseCase->execute($id);
        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        } 

        return response()->json(
            new PurchaseResource($purchase),
            200
        );
    }

    public function store(CreatePurchaseRequest $request): JsonResponse
    {
 
        $purchaseDTO = new PurchaseDTO($request->validated());
        $cretaePurchaseUseCase = new CreatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentTypeRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository
        );

        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);
        return response()->json( 
            (new PurchaseResource($purchase))->resolve(),
            201
        );
    }

    public function update(UpdatePurchaseRequest $request, int $id): JsonResponse
    {

        $purchaseDTO = new PurchaseDTO($request->validated());
        $updatePurchaseUseCase = new UpdatePurchaseUseCase(
            $this->purchaseRepository,
            $this->paymentTypeRepository,
            $this->branchRepository,
            $this->customerRepository,
            $this->currencyRepository,
            $this->documentNumberGeneratorService,
            $this->documentTypeRepository
        );
        $purchase = $updatePurchaseUseCase->execute($purchaseDTO, $id);

        if (!$purchase) {
            return response()->json(['message' => 'Compra no encontrada'], 404);
        } 

        return response()->json(
            (new PurchaseResource($purchase))->resolve(),
            200
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
                'cantidad' => $item['cantidad'],
                'precio_costo' => $item['precio_costo'],
                'descuento' => $item['descuento'],
                'sub_total' => $item['sub_total'],
                'total' => $item['total'],
                'cantidad_update' =>  0,
                'process_status' => 'Pendiente',
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

    public function proovedor(int $id): JsonResponse
    {
        $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
        $purchase = $findByIdPurchaseUseCase->execute($id);
        if (!$purchase) {
            return response()->json(['message' => 'proovedor no encontrado'], 404);
        }
        $guide = $this->detailPurchaseGuideRepository->findById($purchase->getId());
        $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());

        $processStatus = $this->calculateGlobalStatus($guide);

        return response()->json(
            [
                'purchase' => (new PurchaseResource($purchase))->resolve(),
                'process_status' => $processStatus,
                'details' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                'shopping' => ShoppingIncomeGuideResource::collection($shopping)->resolve()
            ],
            200
        )->header('process_status', $processStatus);
    }


    public function updateDetail(int $id): JsonResponse
    {
        // Validate request
        $validated = request()->validate([
            'cantidad_update' => 'required|numeric|min:0',
            'cantidad_update.required' => 'La cantidad a actualizar es requerida',
            'cantidad_update.numeric' => 'La cantidad a actualizar debe ser un número',
            'cantidad_update.min' => 'La cantidad a actualizar debe ser mayor o igual a 0',
        ]);

        $cantidadUpdate = (float) $validated['cantidad_update'];

        // Find detail by ID
        $detail = $this->detailPurchaseGuideRepository->findByDetailId($id);

        if (!$detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detalle de compra no encontrado'
            ], 404);
        }

        // Validate against remaining saldo
        $cantidadOriginal = (float) $detail->getCantidad();
        $consumidoActual = (float) $detail->getCantidadUpdate();
        $saldoDisponible = max(0, $cantidadOriginal - $consumidoActual);
        if ($cantidadUpdate > $saldoDisponible) {
            return response()->json([
                'success' => false,
                'message' => 'La cantidad a actualizar no puede ser mayor que la cantidad disponible',
                'cantidad_disponible' => $saldoDisponible,
                'cantidad_solicitada' => $cantidadUpdate
            ], 422);
        }

        // Calculate new total consumed
        $nuevoConsumidoTotal = $consumidoActual + $cantidadUpdate;

        // Update entity: keep original cantidad, update total consumido
        $detail->setCantidadUpdate($nuevoConsumidoTotal);

        // Save to database
        $updatedDetail = $this->detailPurchaseGuideRepository->save($detail);

        if (!$updatedDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el detalle de compra'
            ], 500);
        }

        // Update entry guide articles saldo via stored procedure
        try {
            $findByIdPurchaseUseCase = new FindByIdPurchaseUseCase($this->purchaseRepository);
            $purchase = $findByIdPurchaseUseCase->execute($detail->getPurchaseId());
            if ($purchase) {
                DB::statement('CALL update_entry_guides_from_purchase(?,?,?,?,?)', [
                    $purchase->getCompanyId(),
                    $purchase->getSupplier()?->getId(),
                    $purchase->getTypeDocumentId()?->getId(),
                    $purchase->getReferenceSerie(),
                    $purchase->getReferenceCorrelative(),
                ]);
            }
        } catch (\Throwable $e) {
            // swallow and still return success; saldo update can be retried
        }

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Detalle de compra actualizado exitosamente',
            'data' => (new DetailPurchaseGuideResource($updatedDetail))->resolve()
        ], 200);
    }

    private function calculateGlobalStatus($details): string
    {
        if (empty($details)) {
            return 'Pendiente';
        }

        $allFacturado = true;
        $allPendiente = true;

        foreach ($details as $detail) {
            $cantidad = method_exists($detail, 'getCantidad') ? $detail->getCantidad() : ($detail['cantidad'] ?? 0);
            $consumido = method_exists($detail, 'getCantidadUpdate') ? $detail->getCantidadUpdate() : ($detail['cantidad_update'] ?? 0);
            $saldo = max(0, $cantidad - $consumido);
            $status = 'Pendiente';
            if ($saldo === 0 && $cantidad > 0) {
                $status = 'Facturado';
            } elseif ($consumido > 0 && $saldo > 0) {
                $status = 'En proceso';
            }

            if ($status !== 'Facturado') {
                $allFacturado = false;
            }
            if ($status !== 'Pendiente') {
                $allPendiente = false;
            }
        }

        if ($allFacturado) {
            return 'Facturado';
        }
        if ($allPendiente) {
            return 'Pendiente';
        }

        return 'En proceso';
    }
}