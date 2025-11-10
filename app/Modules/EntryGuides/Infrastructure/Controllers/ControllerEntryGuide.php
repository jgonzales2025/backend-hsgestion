<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;

use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EntryGuides\Application\DTOS\EntryGuideDTO;
use App\Modules\EntryGuides\Application\UseCases\CreateEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindAllEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\FindByIdEntryGuideUseCase;
use App\Modules\EntryGuides\Application\UseCases\UpdateEntryGuideUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use App\Modules\EntryGuides\Infrastructure\Request\EntryGuideRequest;
use App\Modules\EntryGuides\Infrastructure\Request\UpdateGuideRequest;
use App\Modules\EntryGuides\Infrastructure\Resource\EntryGuideResource;
use App\Modules\PurchaseGuideArticle\Application\DTOS\PurchaseGuideArticleDTO;
use App\Modules\PurchaseGuideArticle\Application\UseCases\CreatePurchaseGuideArticle;
use App\Modules\PurchaseGuideArticle\Domain\Interface\PurchaseGuideArticleRepositoryInterface;
use App\Modules\PurchaseGuideArticle\Infrastructure\Resource\PurchaseGuideArticleResource;
use App\Modules\PurchaseItemSerials\Application\DTOS\PurchaseItemSerialDTO;
use App\Modules\PurchaseItemSerials\Application\UseCases\CreatePurchaseItemSerialUseCase;
use App\Modules\PurchaseItemSerials\Domain\Interface\PurchaseItemSerialRepositoryInterface;
use App\Modules\PurchaseItemSerials\Infrastructure\Resource\PurchaseItemSerialResource;
use Illuminate\Http\JsonResponse;


class ControllerEntryGuide extends Controller
{

    public function __construct(
        private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface,
        private readonly CompanyRepositoryInterface $companyRepositoryInterface,
        private readonly BranchRepositoryInterface $branchRepositoryInterface,
        private readonly CustomerRepositoryInterface $customerRepositoryInterface,
        private readonly PurchaseGuideArticleRepositoryInterface $purchaseGuideArticleRepositoryInterface,
        private readonly PurchaseItemSerialRepositoryInterface $purchaseItemSerialRepositoryInterface,
    ) {
    }

    public function index(): JsonResponse
    {
        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute();

        $result = [];

        foreach ($entryGuide as $entryGuides) {
            $entry = $this->purchaseGuideArticleRepositoryInterface->findById($entryGuides->getId());
            $entry_item_serials = $this->purchaseItemSerialRepositoryInterface->findById($entryGuides->getId());
            $result[] = [
                'entryGuide' => (new EntryGuideResource($entryGuides))->resolve(),
                'purchase_guide_articles' => PurchaseGuideArticleResource::collection($entry)->resolve(),
                'entry_item_serials' => PurchaseItemSerialResource::collection($entry_item_serials)->resolve()

            ];
        }

        return response()->json($result, 200);
    }
    public function show($id): JsonResponse
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        $purchaseArticles = $this->purchaseGuideArticleRepositoryInterface->findById($entryGuide->getId());

        $purchase_item_serial = $this->purchaseItemSerialRepositoryInterface->findById($entryGuide->getId());

        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'purchase_guide_articles' => PurchaseGuideArticleResource::collection($purchaseArticles)->resolve(),
                'entry_item_serials' => PurchaseItemSerialResource::collection($purchase_item_serial)->resolve()

            ],
            200
        );
    }
    public function store(EntryGuideRequest $request): JsonResponse
    {
        $entryGuideDTO = new EntryGuideDTO($request->validated());
        $entryGuideUseCase = new CreateEntryGuideUseCase(
            $this->entryGuideRepositoryInterface,
            $this->companyRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
        );
        $entryGuide = $entryGuideUseCase->execute($entryGuideDTO);

        $createPurchaseGuideArticleUseCase = new CreatePurchaseGuideArticle($this->purchaseGuideArticleRepositoryInterface);
        $PurchaseGuideArticle = array_map(function ($q) use ($entryGuide, $createPurchaseGuideArticleUseCase) {

            $PurchaseGuideArticleDTO = new PurchaseGuideArticleDTO([
                'purchase_guide_id' => $entryGuide->getId(),
                'article_id' => $q['article_id'],
                'description' => $q['description'],
                'quantity' => $q['quantity'],
            ]);
            return $createPurchaseGuideArticleUseCase->execute($PurchaseGuideArticleDTO);
        }, $request->validated()['purchase_guide_articles']);

        $createPurchaseItemSerialGuideArticleUseCase = new CreatePurchaseItemSerialUseCase($this->purchaseItemSerialRepositoryInterface);
        $createPurchaseItemSerialGuideArticleUseCaseR = array_map(function ($q) use ($entryGuide, $createPurchaseItemSerialGuideArticleUseCase) {
            $createPurchaseItemSerialGuideArticleUseCaseDto = new PurchaseItemSerialDTO([
                'purchase_guide_id' => $entryGuide->getId(),
                'article_id' => $q['article_id'],
                'serial' => $q['serial'],
            ]);
            return $createPurchaseItemSerialGuideArticleUseCase->execute($createPurchaseItemSerialGuideArticleUseCaseDto);
        }, $request->validated()['purchase_item_serial']);


        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'purchase_guide_articles' => PurchaseGuideArticleResource::collection($PurchaseGuideArticle)->resolve(),
                'entry_item_serials' => PurchaseItemSerialResource::collection($createPurchaseItemSerialGuideArticleUseCaseR)->resolve()
            ],
            201
        );
    }

    public function update(UpdateGuideRequest $request, $id): JsonResponse
    {
        $entryGuideUseCase = new FindByIdEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute($id);

        if (!$entryGuide) {
            return response()->json(['message' => ' no encontrada'], 404);
        }

        $entryGuideDTO = new EntryGuideDTO($request->validated());
        $entryGuideUseCase = new UpdateEntryGuideUseCase(
            $this->entryGuideRepositoryInterface,
            $this->companyRepositoryInterface,
            $this->branchRepositoryInterface,
            $this->customerRepositoryInterface,
        );

        $entryGuide = $entryGuideUseCase->execute($entryGuideDTO, $id);

        $this->purchaseGuideArticleRepositoryInterface->deleteByPurchaseGuideId($entryGuide->getId());

        $purchaseGuideArticle = $this->createPurchaseGuideArticles($entryGuide, $request->validated()['purchase_guide_articles']);

        $this->purchaseItemSerialRepositoryInterface->deleteByIdPurchaseItemSerial($entryGuide->getId());

        $purchaseGuideArticler = $this->createPurchaseItemSerialGuideArticle($entryGuide, $request->validated()['purchase_item_serial']);

        return response()->json(
            [
                'entryGuide' => (new EntryGuideResource($entryGuide))->resolve(),
                'purchase_guide_articles' => PurchaseGuideArticleResource::collection($purchaseGuideArticle)->resolve(),
                'entry_item_serials' => PurchaseItemSerialResource::collection($purchaseGuideArticler)->resolve()

            ],
            201
        );

    }
    private function createPurchaseGuideArticles($sale, array $purchaseGuideArticle): array
    {

        $createSaleArticleUseCase = new CreatePurchaseGuideArticle($this->purchaseGuideArticleRepositoryInterface);

        return array_map(function ($q) use ($sale, $createSaleArticleUseCase) {
            $saleArticleDTO = new PurchaseGuideArticleDTO([
                'purchase_guide_id' => $sale->getId(),
                'article_id' => $q['article_id'],
                'description' => $q['description'],
                'quantity' => $q['quantity'],
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $purchaseGuideArticle);
    }
    private function createPurchaseItemSerialGuideArticle($sale, array $purchaseGuideArticle): array
    {

        $createSaleArticleUseCase = new CreatePurchaseItemSerialUseCase($this->purchaseItemSerialRepositoryInterface);

        return array_map(function ($q) use ($sale, $createSaleArticleUseCase) {
            $saleArticleDTO = new PurchaseItemSerialDTO([
                'purchase_guide_id' => $sale->getId(),
                'article_id' => $q['article_id'],
                'serial' => $q['serial'],
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $purchaseGuideArticle);
    }
}
