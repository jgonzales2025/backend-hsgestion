<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

use App\Modules\Statistics\Domain\Interfaces\StatisticsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentStatisticsRepository implements StatisticsRepositoryInterface
{
    public function getCustomerConsumedItems(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId)
    {
        $query = DB::table('sales as s')
            ->join('sale_article as sa', 's.id', '=', 'sa.sale_id')
            ->join('articles as a', 'sa.article_id', '=', 'a.id')
            ->join('customers as c', 's.customer_id', '=', 'c.id')
            ->join('branches as b', 's.branch_id', '=', 'b.id')
            ->join('document_types as dt', 's.document_type_id', '=', 'dt.id')
            ->join('currency_types as ct', 's.currency_type_id', '=', 'ct.id')
            ->leftJoin('categories as cat', 'a.category_id', '=', 'cat.id')
            ->leftJoin('brands as br', 'a.brand_id', '=', 'br.id')
            ->where('s.company_id', $company_id)

            ->where('s.status', 1)
            ->whereIn('s.document_type_id', [1, 3]) // Facturas y Boletas
            ->select(
                'c.id as customer_id',
                'c.company_name as customer_name',
                'c.document_number as customer_document',
                'b.name as branch_name',
                'dt.abbreviation as document_type',
                DB::raw("CONCAT(s.serie, '-', s.document_number) as document_number"),
                's.date as sale_date',
                'a.cod_fab as article_code',
                'a.description as article_description',
                'sa.quantity',
                'sa.unit_price',
                'sa.subtotal as total',
                'ct.commercial_symbol as currency_symbol'
            );

        // Apply optional filters
        if ($customerId !== null) {
            $query->where('s.customer_id', $customerId);
        }

        if ($branch_id !== null) {
            $query->where('s.branch_id', $branch_id);
        }

        if ($start_date !== null) {
            $query->where('s.date', '>=', $start_date);
        }

        if ($end_date !== null) {
            $query->where('s.date', '<=', $end_date);
        }

        if ($category_id !== null) {
            $query->where('a.category_id', $category_id);
        }

        if ($brand_id !== null) {
            $query->where('a.brand_id', $brand_id);
        }

        // Order by customer and date
        $query->orderBy('c.company_name')
            ->orderBy('s.date')
            ->orderBy('s.id');

        return $query->get();
    }
}