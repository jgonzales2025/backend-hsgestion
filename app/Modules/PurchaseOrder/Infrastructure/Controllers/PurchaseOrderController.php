<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\PurchaseOrder\Application\DTOs\PurchaseOrderDTO;
use App\Modules\PurchaseOrder\Application\UseCases\CreatePurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\FindAllPurchaseOrdersUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\FindByIdPurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Application\UseCases\UpdatePurchaseOrderUseCase;
use App\Modules\PurchaseOrder\Domain\Interfaces\PurchaseOrderRepositoryInterface;
use App\Modules\PurchaseOrder\Infrastructure\Requests\StorePurchaseOrderRequest;
use App\Modules\PurchaseOrder\Infrastructure\Requests\UpdatePurchaseOrderRequest;
use App\Modules\PurchaseOrder\Infrastructure\Resources\PurchaseOrderResource;
use App\Modules\PurchaseOrderArticle\Application\DTOs\PurchaseOrderArticleDTO;
use App\Modules\PurchaseOrderArticle\Application\UseCases\CreatePurchaseOrderArticleUseCase;
use App\Modules\PurchaseOrderArticle\Application\UseCases\DeleteByPurchaseOrderIdUseCase;
use App\Modules\PurchaseOrderArticle\Application\UseCases\FindPurchaseOrderIdUseCase;
use App\Modules\PurchaseOrderArticle\Domain\Interfaces\PurchaseOrderArticleRepositoryInterface;
use App\Modules\PurchaseOrderArticle\Infrastructure\Resources\PurchaseOrderArticleResource;
use App\Services\DocumentNumberGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function __construct(
        private readonly PurchaseOrderRepositoryInterface $purchaseOrderRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly DocumentNumberGeneratorService $documentNumberGenerator,
        private readonly PurchaseOrderArticleRepositoryInterface $purchaseOrderArticleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly BranchRepositoryInterface $branchRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
         private readonly EntryGuideArticleRepositoryInterface $entryGuideArticleRepositoryInterface,
    ) {
    }

    public function index(): array
    {
        $role = request()->get('role');
        $branches = request()->get('branches');
        $companyId = request()->get('company_id');

        $purchaseOrderUseCase = new FindAllPurchaseOrdersUseCase($this->purchaseOrderRepository);
        $purchaseOrders = $purchaseOrderUseCase->execute($role, $branches, $companyId);

        $result = [];
        foreach ($purchaseOrders as $purchaseOrder) {
            $articles = $this->purchaseOrderArticleRepository->findByPurchaseOrderId($purchaseOrder->getId());

            $response = (new PurchaseOrderResource($purchaseOrder))->resolve();
            $response['articles'] = PurchaseOrderArticleResource::collection($articles)->resolve();

            $result[] = $response;
        }

        return $result;
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $purchaseOrderDTO = new PurchaseOrderDTO($request->validated());
            $purchaseOrderUseCase = new CreatePurchaseOrderUseCase($this->purchaseOrderRepository, $this->customerRepository, $this->documentNumberGenerator, $this->branchRepository, $this->currencyTypeRepository, $this->paymentTypeRepository);
            $purchaseOrder = $purchaseOrderUseCase->execute($purchaseOrderDTO);

            $purchaseOrderArticles = $this->createPurchaseOrderArticles($purchaseOrder, $request->validated()['articles']);

            $response = (new PurchaseOrderResource($purchaseOrder))->resolve();
            $response['articles'] = PurchaseOrderArticleResource::collection($purchaseOrderArticles)->resolve();

            return response()->json($response, 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $purchaseOrderUseCase = new FindByIdPurchaseOrderUseCase($this->purchaseOrderRepository);
        $purchaseOrder = $purchaseOrderUseCase->execute($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Orden de compra no encontrada'], 404);
        }

        $articlesUseCase = new FindPurchaseOrderIdUseCase($this->purchaseOrderArticleRepository);
        $articles = $articlesUseCase->execute($purchaseOrder->getId());

        $response = (new PurchaseOrderResource($purchaseOrder))->resolve();
        $response['articles'] = PurchaseOrderArticleResource::collection($articles)->resolve();

        return response()->json($response, 200);
    }

    public function update(UpdatePurchaseOrderRequest $request, int $id): JsonResponse
    {
        $purchaseOrderDTO = new PurchaseOrderDTO($request->validated());
        $purchaseOrderUseCase = new UpdatePurchaseOrderUseCase($this->purchaseOrderRepository, $this->customerRepository, $this->branchRepository, $this->currencyTypeRepository, $this->paymentTypeRepository);
        $purchaseOrder = $purchaseOrderUseCase->execute($purchaseOrderDTO, $id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Orden de compra no encontrada'], 404);
        }

        $articlesDeleteUseCase = new DeleteByPurchaseOrderIdUseCase($this->purchaseOrderArticleRepository);
        $articlesDeleteUseCase->execute($purchaseOrder->getId());

        $purchaseOrderArticles = $this->createPurchaseOrderArticles($purchaseOrder, $request->validated()['articles']);

        $response = (new PurchaseOrderResource($purchaseOrder))->resolve();
        $response['articles'] = PurchaseOrderArticleResource::collection($purchaseOrderArticles)->resolve();

        return response()->json($response, 201);
    }

    private function createPurchaseOrderArticles($purchaseOrder, array $articlesData): array
    {
        $createPurchaseOrderArticleUseCase = new CreatePurchaseOrderArticleUseCase($this->purchaseOrderArticleRepository);

        return array_map(function ($article) use ($purchaseOrder, $createPurchaseOrderArticleUseCase) {
            $purchaseOrderArticleDTO = new PurchaseOrderArticleDTO([
                'purchase_order_id' => $purchaseOrder->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'weight' => $article['weight'],
                'quantity' => $article['quantity'],
                'purchase_price' => $article['purchase_price'],
                'subtotal' => $article['subtotal'],
            ]);

            return $createPurchaseOrderArticleUseCase->execute($purchaseOrderArticleDTO);
        }, $articlesData);
    }

    public function generatePdf(int $id)
    {
        $purchaseOrderUseCase = new FindByIdPurchaseOrderUseCase($this->purchaseOrderRepository);
        $purchaseOrder = $purchaseOrderUseCase->execute($id);

        if (!$purchaseOrder) {
            return response()->json(['message' => 'Orden de compra no encontrada'], 404);
        }

        $articlesUseCase = new FindPurchaseOrderIdUseCase($this->purchaseOrderArticleRepository);
        $articles = $articlesUseCase->execute($purchaseOrder->getId());

        $company = $this->companyRepository->findById($purchaseOrder->getCompanyId());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase_order', [
            'purchaseOrder' => $purchaseOrder,
            'purchaseOrderArticles' => $articles,
            'company' => $company
        ]);

        return $pdf->stream('orden_compra_' . $purchaseOrder->getCorrelative() . '.pdf');
    }

    
      public function validateSameCustomer(Request $request): JsonResponse
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'Debe enviar un arreglo de IDs válido'], 400);
        }

        $ids = array_map('intval', $ids);

        $isValid = $this->purchaseOrderRepository->allBelongToSameCustomer($ids);

        if (!$isValid) {
            return response()->json(['message' => 'Todos los documentos deben pertenecer al mismo proveedor'], 422);
        }

        $entryGuides = $this->purchaseOrderRepository->findByIds($ids);

        $customerHeader = null;
        foreach ($entryGuides as $entryGuide) {
            if ($customerHeader === null) {
                $customerHeader = [
                    'id' => $entryGuide->getSupplier()?->getId(),
                   

                ];
            }
            $articles = $this->entryGuideArticleRepositoryInterface->findById($entryGuide->getId());

            foreach ($articles as $article) {
                $key = $article->getArticle()->getId();
                if (!isset($aggregated[$key])) {
                    $aggregated[$key] = [
                        'article_id' => $key,
                        'description' => $article->getDescription(),
                        'quantity' => $article->getQuantity(),
                        'cod_fab' => $article->getArticle()->getCodFab(),
                    ];
                } else {
                    $aggregated[$key]['quantity'] += $article->getQuantity();
                }
            }
        }

        return response()->json([
            'customer' => $customerHeader,
             'articles' => array_values($aggregated)
        ], 200);
    }
}
