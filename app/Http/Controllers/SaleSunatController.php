<?php

namespace App\Http\Controllers;

use App\Modules\Company\Application\UseCases\FindByIdCompanyUseCase;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\SaleArticle\Application\UseCases\FindBySaleIdUseCase;
use App\Modules\SaleArticle\Domain\Interfaces\SaleArticleRepositoryInterface;
use App\Services\SalesSunatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SaleSunatController extends Controller
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleInterfaceRepository,
        private readonly SalesSunatService $salesSunatService,
        private readonly SaleArticleRepositoryInterface $saleArticleRepository,
    ) {
    }
    public function store(int $id)
    {
        $sale = new FindByIdSaleUseCase($this->saleInterfaceRepository);
        $sale = $sale->execute($id);

        $saleArticles = new FindBySaleIdUseCase($this->saleArticleRepository);
        $saleArticles = $saleArticles->execute($id);

        if ($sale->getCoddetrac() === null && $sale->getStretencion() === null) {
            Log::info("ESTOY AQUI");
            $response = $this->salesSunatService->saleGravada($sale, $saleArticles);
        }

        if ($sale->getCoddetrac() !== null) {
            Log::info("ESTOY AQUI 2");
            $response = $this->salesSunatService->saleDetraccion($sale, $saleArticles);
        }

        if ($sale->getStretencion() !== null) {
            Log::info("ESTOY AQUI 3");
            $response = $this->salesSunatService->saleRetencion($sale, $saleArticles);
        }
        
        Log::info($response);
        $saleEloquent = EloquentSale::find($id);
        $saleEloquent->update([
            'estado_sunat' => $response['estado'],
            'fecha_aceptacion' => $response['sunat_response']['fecha'] . ' ' . $response['sunat_response']['hora'],
            'respuesta_sunat' => $response['descripcion']
        ]);

        return response()->json($response);
    }
}