<?php

namespace App\Modules\Sale\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Branch\Domain\Interface\BranchRepositoryInterface;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Application\UseCases\CreateSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindAllProformasUseCase;
use App\Modules\Sale\Application\UseCases\FindAllSalesUseCase;
use App\Modules\Sale\Application\UseCases\FindByDocumentSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Application\UseCases\UpdateSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleRequest;
use App\Modules\Sale\Infrastructure\Requests\UpdateSaleRequest;
use App\Modules\Sale\Infrastructure\Resources\SaleResource;
use App\Modules\SaleArticle\Application\DTOs\SaleArticleDTO;
use App\Modules\SaleArticle\Application\UseCases\CreateSaleArticleUseCase;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Resources\SaleArticleResource;
use App\Modules\TransactionLog\Application\DTOs\TransactionLogDTO;
use App\Modules\TransactionLog\Application\UseCases\CreateTransactionLogUseCase;
use App\Modules\TransactionLog\Domain\Interfaces\TransactionLogRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CurrencyTypeRepositoryInterface $currencyTypeRepository,
        private readonly DocumentTypeRepositoryInterface $documentTypeRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly PaymentTypeRepositoryInterface $paymentTypeRepository,
        private readonly SaleArticleRepositoryInterface $saleArticleRepository,
        private readonly TransactionLogRepositoryInterface $transactionLogRepository,
        private readonly BranchRepositoryInterface $branchRepository,
    ){}

    public function index(): JsonResponse
    {
        $saleUseCase = new FindAllSalesUseCase($this->saleRepository);
        $sales = $saleUseCase->execute();

        $result = [];
        foreach ($sales as $sale) {
            $articles = $this->saleArticleRepository->findBySaleId($sale->getId());

            $result[] = [
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($articles)->resolve(),
            ];
        }

        return response()->json($result, 200);
    }

    public function store(StoreSaleRequest $request): JsonResponse
    {
        $userId = request()->get('user_id');
        $role = request()->get('role');

        $saleDTO = new SaleDTO($request->validated());
        $saleUseCase = new CreateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository);
        $sale = $saleUseCase->execute($saleDTO);

        $saleArticles = $this->createSaleArticles($sale, $request->validated()['sale_articles']);
        $this->logTransaction($request, $sale);

        return response()->json([
            'sale' => (new SaleResource($sale))->resolve(),
            'articles' => SaleArticleResource::collection($saleArticles)->resolve()
            ], 201
        );
    }

    public function show($id): JsonResponse
    {
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $articles = $this->saleArticleRepository->findBySaleId($sale->getId());

        return response()->json(
            [
                'sale' => (new SaleResource($sale))->resolve(),
                'articles' => SaleArticleResource::collection($articles)->resolve(),
            ]
        );
    }

    public function update(UpdateSaleRequest $request, $id): JsonResponse
    {
        $saleUseCase = new FindByIdSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($id);

        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        if ($sale->getIsLocked() == 0) {
            return response()->json(['message' => 'La venta no se puede actualizar por cierre de mes'], 200);
        }

        $saleDTO = new SaleDTO($request->validated());
        $saleUseCase = new UpdateSaleUseCase($this->saleRepository, $this->companyRepository, $this->branchRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository);
        $saleUpdated = $saleUseCase->execute($saleDTO, $sale);

        $this->saleArticleRepository->deleteBySaleId($saleUpdated->getId());

        $saleArticles = $this->createSaleArticles($saleUpdated, $request->validated()['sale_articles']);
        $this->logTransaction($request, $saleUpdated);

        return response()->json([
            'sale' => (new SaleResource($saleUpdated))->resolve(),
            'articles' => SaleArticleResource::collection($saleArticles)->resolve()
            ], 200
        );
    }

    public function showDocumentSale(Request $request): JsonResponse
    {
        $documentTypeId = $request->query('document_type_id');
        $serie = $request->query('serie');
        $correlative = $request->query('correlative');

        $saleUseCase = new FindByDocumentSaleUseCase($this->saleRepository);
        $sale = $saleUseCase->execute($documentTypeId, $serie, $correlative);
        if (!$sale) {
            return response()->json(['message' => 'Venta no encontrada'], 404);
        }

        $articles = $this->saleArticleRepository->findBySaleId($sale->getId());

        return response()->json([
            'sale' => (new SaleResource($sale))->resolve(),
            'articles' => SaleArticleResource::collection($articles)->resolve()
        ]);
    }

    private function createSaleArticles($sale, array $articlesData): array
    {
        $createSaleArticleUseCase = new CreateSaleArticleUseCase($this->saleArticleRepository);

        return array_map(function ($article) use ($sale, $createSaleArticleUseCase) {
            $saleArticleDTO = new SaleArticleDTO([
                'sale_id' => $sale->getId(),
                'article_id' => $article['article_id'],
                'description' => $article['description'],
                'quantity' => $article['quantity'],
                'unit_price' => $article['unit_price'],
                'public_price' => $article['public_price'],
                'subtotal' => $article['subtotal'],
            ]);

            return $createSaleArticleUseCase->execute($saleArticleDTO);
        }, $articlesData);
    }

    private function logTransaction($request, $sale): void
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
            'description_log' => 'Venta',
            'action' => $request->method(),
            'company_id' => $sale->getCompany()->getId(),
            'branch_id' => $sale->getBranch()->getId(),
            'document_type_id' => $sale->getDocumentType()->getId(),
            'serie' => $sale->getSerie(),
            'correlative' => $sale->getDocumentNumber(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $transactionLogs->execute($transactionDTO);
    }

    public function indexProformas(): array
    {
        $saleUseCase = new FindAllProformasUseCase($this->saleRepository);
        $sales = $saleUseCase->execute();

        return SaleResource::collection($sales)->resolve();
    }
}
