<?php

namespace App\Modules\Dashboard\Infrastructure\Persistence;

use App\Modules\Dashboard\Domain\Interfaces\DashboardRepositoryInterface;
use App\Modules\SaleArticle\Infrastructure\Models\EloquentSaleArticle;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\Purchases\Infrastructure\Models\EloquentPurchase;
use Illuminate\Support\Facades\DB;

class EloquentDashboardRepository implements DashboardRepositoryInterface
{
    public function countProductsSoldByCategory(int $company_id): array
    {
        return EloquentSaleArticle::query()
            ->join('sales', 'sale_article.sale_id', '=', 'sales.id')
            ->join('articles', 'sale_article.article_id', '=', 'articles.id')
            ->join('categories', 'articles.category_id', '=', 'categories.id')
            ->where('sales.status', 1)
            ->where('sales.company_id', $company_id)
            ->select('categories.name as category_name', DB::raw('SUM(sale_article.quantity) as total_quantity'))
            ->groupBy('categories.name')
            ->get()
            ->toArray();
    }

    public function getTopSellingProducts(int $company_id): array
    {
        return EloquentSaleArticle::query()
            ->join('sales', 'sale_article.sale_id', '=', 'sales.id')
            ->join('articles', 'sale_article.article_id', '=', 'articles.id')
            ->where('sales.status', 1)
            ->where('sales.company_id', $company_id)
            ->select('articles.description as article_name', DB::raw('SUM(sale_article.quantity) as total_quantity'))
            ->groupBy('articles.description')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getSalesPurchasesAndUtility(int $company_id, string $start_date, string $end_date): array
    {
        $salesData = EloquentSale::query()
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->whereBetween('date', [$start_date, $end_date])
            ->select(
                DB::raw('SUM(CASE 
                    WHEN currency_type_id = 1 THEN total 
                    WHEN currency_type_id = 2 THEN total * parallel_rate 
                    ELSE 0 
                END) as total_sales_in_soles'),
                DB::raw('SUM(CASE 
                    WHEN currency_type_id = 2 THEN total 
                    WHEN currency_type_id = 1 AND parallel_rate > 0 THEN total / parallel_rate 
                    ELSE 0 
                END) as total_sales_in_dollars')
            )
            ->first();

        $purchasesData = EloquentPurchase::query()
            ->join('branches', 'purchase.branch_id', '=', 'branches.id')
            ->where('branches.cia_id', $company_id)
            ->whereBetween('purchase.date', [$start_date, $end_date])
            ->select(
                DB::raw('SUM(CASE 
                    WHEN purchase.currency = 1 THEN purchase.total 
                    WHEN purchase.currency = 2 THEN purchase.total * purchase.exchange_type 
                    ELSE 0 
                END) as total_purchases_in_soles'),
                DB::raw('SUM(CASE 
                    WHEN purchase.currency = 2 THEN purchase.total 
                    WHEN purchase.currency = 1 AND purchase.exchange_type > 0 THEN purchase.total / purchase.exchange_type 
                    ELSE 0 
                END) as total_purchases_in_dollars')
            )
            ->first();

        $totalSalesSoles = $salesData->total_sales_in_soles ?? 0;
        $totalSalesDollars = $salesData->total_sales_in_dollars ?? 0;

        $totalPurchasesSoles = $purchasesData->total_purchases_in_soles ?? 0;
        $totalPurchasesDollars = $purchasesData->total_purchases_in_dollars ?? 0;

        return [
            'total_sales_pen' => round($totalSalesSoles, 2),
            'total_purchases_pen' => round($totalPurchasesSoles, 2),
            'utility_pen' => round($totalSalesSoles - $totalPurchasesSoles, 2),
            'cost_pen' => 0,
            'total_sales_usd' => round($totalSalesDollars, 2),
            'total_purchases_usd' => round($totalPurchasesDollars, 2),
            'utility_usd' => round($totalSalesDollars - $totalPurchasesDollars, 2),
            'cost_usd' => 0
        ];
    }

    public function getTopCustomers(int $company_id): array
    {
        return EloquentSale::query()
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.company_id', $company_id)
            ->where('sales.status', 1)
            ->select(
                DB::raw("CASE 
                    WHEN customers.company_name IS NOT NULL AND customers.company_name != '' THEN customers.company_name 
                    ELSE CONCAT(customers.name, ' ', customers.lastname) 
                END as customer_name"),
                DB::raw('SUM(CASE 
                    WHEN sales.currency_type_id = 1 THEN sales.total 
                    WHEN sales.currency_type_id = 2 THEN sales.total * sales.parallel_rate 
                    ELSE 0 
                END) as total_sales_in_soles')
            )
            ->groupBy('customers.id', 'customers.company_name', 'customers.name', 'customers.lastname')
            ->orderBy('total_sales_in_soles', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
}