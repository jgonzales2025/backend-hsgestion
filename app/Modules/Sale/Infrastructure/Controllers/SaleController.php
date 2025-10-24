<?php

namespace App\Modules\Sale\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\CurrencyType\Domain\Interfaces\CurrencyTypeRepositoryInterface;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\PaymentType\Domain\Interfaces\PaymentTypeRepositoryInterface;
use App\Modules\Sale\Application\DTOs\SaleDTO;
use App\Modules\Sale\Application\UseCases\CreateSaleUseCase;
use App\Modules\Sale\Application\UseCases\FindAllSalesUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Requests\StoreSaleRequest;
use App\Modules\Sale\Infrastructure\Resources\SaleResource;
use App\Modules\SaleArticle\Application\DTOs\SaleArticleDTO;
use App\Modules\SaleArticle\Application\UseCases\CreateSaleArticleUseCase;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Resources\SaleArticleResource;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
        $saleDTO = new SaleDTO($request->validated());
        $saleUseCase = new CreateSaleUseCase($this->saleRepository, $this->companyRepository, $this->userRepository, $this->currencyTypeRepository, $this->documentTypeRepository, $this->customerRepository, $this->paymentTypeRepository);
        $sale = $saleUseCase->execute($saleDTO);

        $createSaleArticleUseCase = new CreateSaleArticleUseCase($this->saleArticleRepository);
        $saleArticles = array_map(function ($article) use ($sale, $createSaleArticleUseCase) {
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
        }, $request->validated()['sale_articles']);

        return response()->json([
            'sale' => (new SaleResource($sale))->resolve(),
            'articles' => SaleArticleResource::collection($saleArticles)->resolve()
            ], 201
        );
    }
}
