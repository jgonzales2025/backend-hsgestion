<?php

namespace App\Modules\Dashboard\Infrastructure\Persistence;

use App\Modules\Collections\Infrastructure\Models\EloquentCollection;
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
            ->where('sales.document_type_id', '!=', 16)
            ->where('sales.company_id', $company_id)
            ->select('categories.name as category_name', DB::raw('SUM(CASE WHEN sales.document_type_id = 7 THEN -sale_article.quantity ELSE sale_article.quantity END) as total_quantity'))
            ->orderBy('total_quantity', 'desc')
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
            ->where('sales.document_type_id', '!=', 16)
            ->where('sales.company_id', $company_id)
            ->select('articles.description as article_name', DB::raw('SUM(CASE WHEN sales.document_type_id = 7 THEN -sale_article.quantity ELSE sale_article.quantity END) as total_quantity'))
            ->groupBy('articles.description')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getSalesPurchasesAndUtility(int $company_id, string $start_date, string $end_date): array
    {
        // Obtener ventas agrupadas por mes
        $salesData = EloquentSale::query()
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->where('document_type_id', '!=', 16)
            ->whereBetween('date', [$start_date, $end_date])
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM((CASE WHEN document_type_id = 7 THEN -1 ELSE 1 END) * (CASE 
                    WHEN currency_type_id = 1 THEN total 
                    WHEN currency_type_id = 2 THEN total * parallel_rate 
                    ELSE 0 
                END)) as total_sales_in_soles'),
                DB::raw('SUM((CASE WHEN document_type_id = 7 THEN -1 ELSE 1 END) * (CASE 
                    WHEN currency_type_id = 2 THEN total 
                    WHEN currency_type_id = 1 AND parallel_rate > 0 THEN total / parallel_rate 
                    ELSE 0 
                END)) as total_sales_in_dollars')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Obtener compras agrupadas por mes
        $purchasesData = EloquentPurchase::query()
            ->join('branches', 'purchase.branch_id', '=', 'branches.id')
            ->where('branches.cia_id', $company_id)
            ->whereBetween('purchase.date', [$start_date, $end_date])
            ->select(
                DB::raw('DATE_FORMAT(purchase.date, "%Y-%m") as month'),
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
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Obtener costos (total_costo_neto) de ventas agrupados por mes
        $costsData = EloquentSale::query()
            ->where('company_id', $company_id)
            ->where('status', 1)
            ->where('document_type_id', '!=', 16)
            ->whereBetween('date', [$start_date, $end_date])
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM((CASE WHEN document_type_id = 7 THEN -1 ELSE 1 END) * (CASE 
                    WHEN currency_type_id = 1 THEN total_costo_neto 
                    WHEN currency_type_id = 2 THEN total_costo_neto * parallel_rate 
                    ELSE 0 
                END)) as total_costs_in_soles'),
                DB::raw('SUM((CASE WHEN document_type_id = 7 THEN -1 ELSE 1 END) * (CASE 
                    WHEN currency_type_id = 2 THEN total_costo_neto 
                    WHEN currency_type_id = 1 AND parallel_rate > 0 THEN total_costo_neto / parallel_rate 
                    ELSE 0 
                END)) as total_costs_in_dollars')
            )
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Combinar datos por mes
        $months = $salesData->keys()->merge($purchasesData->keys())->merge($costsData->keys())->unique()->sort()->values();

        $monthlyData = [];
        foreach ($months as $month) {
            $salesSoles = $salesData->get($month)->total_sales_in_soles ?? 0;
            $salesDollars = $salesData->get($month)->total_sales_in_dollars ?? 0;
            $purchasesSoles = $purchasesData->get($month)->total_purchases_in_soles ?? 0;
            $purchasesDollars = $purchasesData->get($month)->total_purchases_in_dollars ?? 0;
            $costsSoles = $costsData->get($month)->total_costs_in_soles ?? 0;
            $costsDollars = $costsData->get($month)->total_costs_in_dollars ?? 0;

            $monthlyData[] = [
                'month' => $month,
                'total_sales_pen' => round($salesSoles, 2),
                'total_purchases_pen' => round($purchasesSoles, 2),
                'utility_pen' => round($salesSoles - $costsSoles, 2),
                'cost_pen' => round($costsSoles, 2),
                'total_sales_usd' => round($salesDollars, 2),
                'total_purchases_usd' => round($purchasesDollars, 2),
                'utility_usd' => round($salesDollars - $costsDollars, 2),
                'cost_usd' => round($costsDollars, 2)
            ];
        }

        return $monthlyData;
    }

    public function getTopCustomers(int $company_id): array
    {
        return EloquentSale::query()
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.company_id', $company_id)
            ->where('sales.status', 1)
            ->where('sales.document_type_id', '!=', 16)
            ->select(
                DB::raw("CASE 
                    WHEN customers.company_name IS NOT NULL AND customers.company_name != '' THEN customers.company_name 
                    ELSE CONCAT(customers.name, ' ', customers.lastname) 
                END as customer_name"),
                DB::raw('SUM((CASE WHEN sales.document_type_id = 7 THEN -1 ELSE 1 END) * (CASE 
                    WHEN sales.currency_type_id = 1 THEN sales.total 
                    WHEN sales.currency_type_id = 2 THEN sales.total * sales.parallel_rate 
                    ELSE 0 
                END)) as total_sales_in_soles')
            )
            ->groupBy('customers.id', 'customers.company_name', 'customers.name', 'customers.lastname')
            ->orderBy('total_sales_in_soles', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }
    
    public function getDetailByDocuments(int $company_id): array
    {
        $resultados = EloquentSale::selectRaw('
                document_type_id,
                COUNT(*) as cantidad,
                SUM(CASE 
                    WHEN currency_type_id = 1 THEN total 
                    WHEN currency_type_id = 2 THEN total * parallel_rate 
                    ELSE 0 
                END) as monto_soles,
                SUM(CASE 
                    WHEN currency_type_id = 2 THEN total 
                    WHEN currency_type_id = 1 AND parallel_rate > 0 THEN total / parallel_rate 
                    ELSE 0 
                END) as monto_dolares
            ')
                ->where('company_id', $company_id)
                ->where('status', 1)
                ->whereIn('document_type_id', [1, 3, 7, 8, 17])
                ->groupBy('document_type_id')
                ->get()
                ->keyBy('document_type_id');
        
            return [
                'monto_facturas_soles' => round($resultados[1]->monto_soles ?? 0, 2),
                'monto_facturas_dolares' => round($resultados[1]->monto_dolares ?? 0, 2),
                'cantidad_facturas' => $resultados[1]->cantidad ?? 0,
                'monto_boletas_soles' => round($resultados[3]->monto_soles ?? 0, 2),
                'monto_boletas_dolares' => round($resultados[3]->monto_dolares ?? 0, 2),
                'cantidad_boletas' => $resultados[3]->cantidad ?? 0,
                'monto_notas_credito_soles' => round(-($resultados[7]->monto_soles ?? 0), 2),
                'monto_notas_credito_dolares' => round(-($resultados[7]->monto_dolares ?? 0), 2),
                'cantidad_notas_credito' => $resultados[7]->cantidad ?? 0,
                'monto_notas_debito_soles' => round($resultados[8]->monto_soles ?? 0, 2),
                'monto_notas_debito_dolares' => round($resultados[8]->monto_dolares ?? 0, 2),
                'cantidad_notas_debito' => $resultados[8]->cantidad ?? 0,
                'monto_notas_venta_soles' => round($resultados[17]->monto_soles ?? 0, 2),
                'monto_notas_venta_dolares' => round($resultados[17]->monto_dolares ?? 0, 2),
                'cantidad_notas_venta' => $resultados[17]->cantidad ?? 0,
                'total_transacciones' => ($resultados[1]->cantidad ?? 0) + ($resultados[3]->cantidad ?? 0) + ($resultados[7]->cantidad ?? 0) + ($resultados[8]->cantidad ?? 0) + ($resultados[17]->cantidad ?? 0),
            ];
    }
    
    public function getDetailByPaymentMethods(int $company_id): array
    {
        $resultados = EloquentCollection::selectRaw('
                payment_method_id,
                SUM(CASE 
                    WHEN currency_type_id = 1 THEN amount 
                    WHEN currency_type_id = 2 THEN amount * parallel_rate 
                    ELSE 0 
                END) as monto_soles,
                SUM(CASE 
                    WHEN currency_type_id = 2 THEN amount 
                    WHEN currency_type_id = 1 AND parallel_rate > 0 THEN amount / parallel_rate 
                    ELSE 0 
                END) as monto_dolares
            ')
                ->where('company_id', $company_id)
                ->groupBy('payment_method_id')
                ->get()
                ->keyBy('payment_method_id');
        
        return [
            'monto_efectivo_soles' => round($resultados[1]->monto_soles ?? 0, 2),
            'monto_efectivo_dolares' => round($resultados[1]->monto_dolares ?? 0, 2),
            'monto_tarjeta_soles' => round($resultados[2]->monto_soles ?? 0, 2),
            'monto_tarjeta_dolares' => round($resultados[2]->monto_dolares ?? 0, 2),
            'monto_deposito_soles' => round($resultados[3]->monto_soles ?? 0, 2),
            'monto_deposito_dolares' => round($resultados[3]->monto_dolares ?? 0, 2),
            'monto_billetera_soles' => round($resultados[4]->monto_soles ?? 0, 2),
            'monto_billetera_dolares' => round($resultados[4]->monto_dolares ?? 0, 2),
        ];
    }
}