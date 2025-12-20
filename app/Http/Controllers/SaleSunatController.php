<?php

namespace App\Http\Controllers;

use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\SaleArticle\Application\UseCases\FindBySaleIdUseCase;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Services\SalesSunatService;
use Illuminate\Http\JsonResponse;

class SaleSunatController extends Controller
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleInterfaceRepository,
        private readonly SalesSunatService $salesSunatService,
        private readonly SaleArticleRepositoryInterface $saleArticleRepository
    ) {
    }
    public function store(int $id)
    {
        $sale = new FindByIdSaleUseCase($this->saleInterfaceRepository);
        $sale = $sale->execute($id);

        $saleArticles = new FindBySaleIdUseCase($this->saleArticleRepository);
        $saleArticles = $saleArticles->execute($id);

        if ($sale->getCoddetrac() === null || $sale->getStretencion() === null) {
            $response = $this->salesSunatService->saleGravada($sale, $saleArticles);
        }

        return response()->json($response);
    }
}