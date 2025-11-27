<?php

namespace App\Modules\PurchaseOrder\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Domain\Interfaces\ArticleRepositoryInterface;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\EntryGuideArticle\Domain\Interface\EntryGuideArticleRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
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
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Application\UseCases\FindByDocumentUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
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
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly ArticleRepositoryInterface $articleRepositoryInterface
    ) {}

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
            $this->logTransaction($request, $purchaseOrder);

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
        return DB::transaction(function () use ($request, $id) {
            $purchaseOrderDTO = new PurchaseOrderDTO($request->validated());
            $purchaseOrderUseCase = new UpdatePurchaseOrderUseCase($this->purchaseOrderRepository, $this->customerRepository, $this->branchRepository, $this->currencyTypeRepository, $this->paymentTypeRepository);
            $purchaseOrder = $purchaseOrderUseCase->execute($purchaseOrderDTO, $id);

            if (!$purchaseOrder) {
                return response()->json(['message' => 'Orden de compra no encontrada'], 404);
            }

            $articlesDeleteUseCase = new DeleteByPurchaseOrderIdUseCase($this->purchaseOrderArticleRepository);
            $articlesDeleteUseCase->execute($purchaseOrder->getId());

            $purchaseOrderArticles = $this->createPurchaseOrderArticles($purchaseOrder, $request->validated()['articles']);
            $this->logTransaction($request, $purchaseOrder);

            $response = (new PurchaseOrderResource($purchaseOrder))->resolve();
            $response['articles'] = PurchaseOrderArticleResource::collection($purchaseOrderArticles)->resolve();

            return response()->json($response, 201);
        });
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

        $transactionLogUseCase = new FindByDocumentUseCase($this->transactionLogRepository);
        $transactionLog = $transactionLogUseCase->execute($purchaseOrder->getSerie(), $purchaseOrder->getCorrelative());

        $company = $this->companyRepository->findById($purchaseOrder->getCompanyId());

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('purchase_order', [
            'purchaseOrder' => $purchaseOrder,
            'purchaseOrderArticles' => $articles,
            'company' => $company,
            'transactionLog' => $transactionLog
        ]);

        return $pdf->stream('orden_compra_' . $purchaseOrder->getCorrelative() . '.pdf');
    }

    /**
     * Get purchase orders by IDs and aggregate articles
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getBySupplier(Request $request): JsonResponse
    {
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['message' => 'Debe enviar un arreglo de IDs válido'], 400);
        }

        $ids = array_map('intval', $ids);

        // Get all purchase orders by IDs
        $purchaseOrders = $this->purchaseOrderRepository->findByIds($ids);

        if (empty($purchaseOrders)) {
            return response()->json([
                'message' => 'No se encontraron órdenes de compra con los IDs proporcionados',
                'ids' => $ids
            ], 404);
        }

        // Validate all belong to same supplier
        $isValid = $this->purchaseOrderRepository->allBelongToSameCustomer($ids);

        if (!$isValid) {
            return response()->json(['message' => 'Todos los documentos deben pertenecer al mismo proveedor'], 422);
        }

        // Get supplier info from first purchase order
        $supplierInfo = [
            'id' => $purchaseOrders[0]->getSupplier()?->getId(),
            'name' => $purchaseOrders[0]->getSupplier()?->getName(),
        ];

        // Aggregate articles
        $aggregated = [];
        foreach ($purchaseOrders as $purchaseOrder) {
            $articles = $this->purchaseOrderArticleRepository->findByPurchaseOrderId($purchaseOrder->getId());

            foreach ($articles as $article) {
                $articleEntity = $this->articleRepositoryInterface->findById($article->getArticleId());

                if (!isset($aggregated[$articleEntity->getId()])) {
                    // First time seeing this article
                    $aggregated[$articleEntity->getId()] = [
                        'article_id' => $articleEntity->getId(),
                        'description' => $article->getDescription(),
                        'quantity' => $article->getQuantity(),
                        'weight' => $article->getWeight(),
                        'purchase_price' => $article->getPurchasePrice(),
                        'cod_fab' => $article->getCodFab(),
                        'series_enabled' => $articleEntity->getSeriesEnabled()
                    ];
                } else {
                    // Article already exists, sum the quantity
                    $aggregated[$articleEntity->getId()]['quantity'] += $article->getQuantity();
                }
            }
        }

        return response()->json([
            'supplier' => $supplierInfo,
            'total_orders' => count($purchaseOrders),
            'articles' => array_values($aggregated)
        ], 200);
    }


    private function logTransaction($request, $purchaseOrder): void
    {
        $transactionLogs = new CreateTransactionLogUseCase(
            $this->transactionLogRepository,
            $this->userRepository,
            $this->companyRepository,
            $this->documentTypeRepository,
            $this->branchRepository
        );

        $transactionDTO = new TransactionLogDTO([
            'user_id' => request()->get('user_id'),
            'role_name' => request()->get('role'),
            'description_log' => 'Orden de Compra',
            'action' => $request->method(),
            'company_id' => $purchaseOrder->getCompanyId(),
            'branch_id' => $purchaseOrder->getBranch()->getId(),
            'document_type_id' => 20,
            'serie' => $purchaseOrder->getSerie(),
            'correlative' => $purchaseOrder->getCorrelative(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);
    }
}
