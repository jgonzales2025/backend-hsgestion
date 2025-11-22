<?php

namespace App\Modules\Purchases\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
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
use App\Modules\Purchases\Infrastructure\Request\PudatePurchaseRequest;
use App\Modules\Purchases\Infrastructure\Resource\PurchaseResource;
use App\Modules\ShoppingIncomeGuide\Application\DTOS\ShoppingIncomeGuideDTO;
use App\Modules\ShoppingIncomeGuide\Application\UseCases\CreateShoppingIncomeGuideUseCase;
use App\Modules\ShoppingIncomeGuide\Domain\Interface\ShoppingIncomeGuideRepositoryInterface;
use App\Modules\ShoppingIncomeGuide\Infrastructure\Resource\ShoppingIncomeGuideResource;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseRepositoryInterface $purchaseRepository,
        private readonly ShoppingIncomeGuideRepositoryInterface $shoppingIncomeGuideRepository,
        private readonly DetailPurchaseGuideRepositoryInterface $detailPurchaseGuideRepository,
        private readonly PaymentMethodRepositoryInterface $paymentMethodRepository
    ) {}
    public function index(): JsonResponse
    {
        $findAllPurchaseUseCase = new FindAllPurchaseUseCase($this->purchaseRepository);
        $purchases = $findAllPurchaseUseCase->execute();

        $result = [];
        foreach ($purchases as $purchase) {
            $guide = $this->detailPurchaseGuideRepository->findById($purchase->getId());
            $shopping = $this->shoppingIncomeGuideRepository->findById($purchase->getId());
            $result[] = [
                'purchase' => (new PurchaseResource($purchase))->resolve(),
                'details' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                'shopping' => ShoppingIncomeGuideResource::collection($shopping)->resolve()

            ];
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

        return response()->json(
            [
                'purchase' => (new PurchaseResource($purchase))->resolve(),
                'details' => DetailPurchaseGuideResource::collection($guide)->resolve(),
                'shopping' => ShoppingIncomeGuideResource::collection($shopping)->resolve()

            ],
            200
        );
    }
    public function store(CreatePurchaseRequest $request): JsonResponse
    {
        $purchaseDTO = new PurchaseDTO($request->validated());
        $cretaePurchaseUseCase = new CreatePurchaseUseCase($this->purchaseRepository, $this->paymentMethodRepository);
        $purchase = $cretaePurchaseUseCase->execute($purchaseDTO);

        $det_compras_guia_ingreso = $this->createDetComprasGuiaIngreso($purchase, $request->validated()['det_compras_guia_ingreso']);

        $shopping_income_guide = $this->createShoppingIncomeGuide($purchase,  $request['entry_guide_id']);


        return response()->json(
            [
                'purchase' => new PurchaseResource($purchase),
                'purchaseGuide' => DetailPurchaseGuideResource::collection($det_compras_guia_ingreso)->resolve(),
                'shoppingGuide' => new ShoppingIncomeGuideResource($shopping_income_guide)

            ],
            201
        );
    }
    public function update(PudatePurchaseRequest $request, int $id): JsonResponse
    {
        $purchaseDTO = new PurchaseDTO($request->validated());
        $updatePurchaseUseCase = new UpdatePurchaseUseCase($this->purchaseRepository);
        $purchase = $updatePurchaseUseCase->execute($purchaseDTO, $id);

        $this->detailPurchaseGuideRepository->deletedBy($purchase->getId());

        $this->shoppingIncomeGuideRepository->deletedBy($purchase->getId());

        $detailcompras = $this->updateDetailCompra($purchase, $request->validated()['det_compras_guia_ingreso']);
        $shopping = $this->updateShopping($purchase, $request->validated()['shopping_income_guide']);

        return response()->json(
            [
                'purchase' => new PurchaseResource($purchase),
                'purchaseGuide' => DetailPurchaseGuideResource::collection($detailcompras)->resolve(),
                'shoppingGuide' => ShoppingIncomeGuideResource::collection($shopping)->resolve()

            ],
            201
        );
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

        return array_map(function ($purchase) use ($shooping, $createShooping) {
            $shoopingDTO = new ShoppingIncomeGuideDTO([
                'purchase_id' => $shooping->getId(),
                'entry_guide_id' => $purchase->getEntryGuideId(),
            ]);

            $shooping = $createShooping->execute($shoopingDTO);
            return $shooping;
        }, $data);
    }
    private function updateDetailCompra($detail, array $data): array
    {

        $createDetail = new CreateDetailPurchaseGuideUseCase($this->detailPurchaseGuideRepository);

        return array_map(function ($purchase) use ($detail, $createDetail) {
            $detailDto = new DetailPurchaseGuideDTO([
                'purchase_id' => $detail->getId(),
                'article_id' => $purchase['article_id'],
                'description' => $purchase['description'],
                'cantidad' => $purchase['cantidad'],
                'precio_costo' => $purchase['precio_costo'],
                'descuento' => $purchase['descuento'],
                'sub_total' => $purchase['sub_total'],
            ]);

            $createDetail = $createDetail->execute($detailDto);

            return $createDetail;
        }, $data);
    }
}
