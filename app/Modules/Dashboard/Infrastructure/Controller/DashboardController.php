<?php

namespace App\Modules\Dashboard\Infrastructure\Controller;

use App\Modules\Dashboard\Application\UseCases\countProductsSoldByCategoryUseCase;
use App\Modules\Dashboard\Application\UseCases\GetDetailByDocumentsUseCase;
use App\Modules\Dashboard\Application\UseCases\GetDetailByPaymentMethodsUseCase;
use App\Modules\Dashboard\Application\UseCases\GetTopSellingProductsUseCase;
use App\Modules\Dashboard\Application\UseCases\GetSalesPurchasesAndUtilityUseCase;
use App\Modules\Dashboard\Application\UseCases\GetTopCustomersUseCase;
use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController
{
    public function __construct(private DashboardRepositoryInterface $dashboardRepository)
    {
    }
    public function countProductsSoldByCategory(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');

        $countProductsByCategoryUseCase = new countProductsSoldByCategoryUseCase($this->dashboardRepository);
        $countProductsByCategory = $countProductsByCategoryUseCase->execute($company_id);

        return response()->json($countProductsByCategory);
    }

    public function topTenSellingProducts(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');

        $topSellingProductsUseCase = new GetTopSellingProductsUseCase($this->dashboardRepository);
        $topSellingProducts = $topSellingProductsUseCase->execute($company_id);

        return response()->json($topSellingProducts);
    }

    public function getSalesPurchasesAndUtility(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $getSalesPurchasesAndUtilityUseCase = new GetSalesPurchasesAndUtilityUseCase($this->dashboardRepository);
        $data = $getSalesPurchasesAndUtilityUseCase->execute($company_id, $start_date, $end_date);

        return response()->json($data);
    }

    public function getTopTenCustomers(Request $request): JsonResponse
    {
        $company_id = request()->get('company_id');

        $getTopCustomersUseCase = new GetTopCustomersUseCase($this->dashboardRepository);
        $data = $getTopCustomersUseCase->execute($company_id);

        return response()->json($data);
    }
    
    public function getDetailByDocuments(): JsonResponse
    {
        $company_id = request()->get('company_id');
        
        $getDetailByDocumentsUseCase = new GetDetailByDocumentsUseCase($this->dashboardRepository);
        $data = $getDetailByDocumentsUseCase->execute($company_id);

        return response()->json($data);
    }
    
    public function getDetailByPaymentMethods(): JsonResponse
    {
        $company_id = request()->get('company_id');
        
        $getDetailByPaymentMethodsUseCase = new GetDetailByPaymentMethodsUseCase($this->dashboardRepository);
        $data = $getDetailByPaymentMethodsUseCase->execute($company_id);

        return response()->json($data);
    }
}