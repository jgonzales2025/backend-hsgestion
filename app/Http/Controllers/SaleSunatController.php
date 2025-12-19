<?php

namespace App\Http\Controllers;

use App\Modules\Sale\Application\UseCases\FindByIdSaleUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use Illuminate\Http\JsonResponse;

class SaleSunatController extends Controller
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleInterfaceRepository,
    ) {
    }
    public function index(int $id): JsonResponse
    {
        $sale = new FindByIdSaleUseCase($this->saleInterfaceRepository);
        $sale = $sale->execute($id);
    }
}