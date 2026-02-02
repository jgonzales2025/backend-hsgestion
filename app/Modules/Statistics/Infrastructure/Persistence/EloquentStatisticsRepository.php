<?php

namespace App\Modules\Statistics\Infrastructure\Persistence;

use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\Statistics\Domain\Interfaces\StatisticsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class EloquentStatisticsRepository implements StatisticsRepositoryInterface
{
    public function getCustomerConsumedItems(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId)
    {
        $customerNameExpression = "COALESCE(NULLIF(c.company_name, ''), NULLIF(TRIM(CONCAT_WS(' ', c.name, c.lastname, c.second_lastname)), ''))";

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
                DB::raw("{$customerNameExpression} as customer_name"),
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
        $query->orderByRaw($customerNameExpression)
            ->orderBy('s.date')
            ->orderBy('s.id');

        return $query->get();
    }

    public function getCustomerConsumedItemsPaginated(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId, int $perPage = 15)
    {
        $customerNameExpression = "COALESCE(NULLIF(c.company_name, ''), NULLIF(TRIM(CONCAT_WS(' ', c.name, c.lastname, c.second_lastname)), ''))";

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
                DB::raw("{$customerNameExpression} as customer_name"),
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
        $query->orderByRaw($customerNameExpression)
            ->orderBy('s.date')
            ->orderBy('s.id');

        return $query->paginate($perPage);
    }

    public function getArticlesSold(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $article_id, ?string $description, int $perPage = 10)
    {
        $query = DB::table('sales as s')
            ->join('sale_article as sa', 's.id', '=', 'sa.sale_id')
            ->join('articles as a', 'sa.article_id', '=', 'a.id')
            ->leftJoin('users as u', 's.user_sale_id', '=', 'u.id')
            ->leftJoin('categories as cat', 'a.category_id', '=', 'cat.id')
            ->leftJoin('brands as br', 'a.brand_id', '=', 'br.id')
            ->where('s.company_id', $company_id)
            ->where('s.status', 1)
            ->whereIn('s.document_type_id', [1, 3]) // Facturas y Boletas
            ->select(
                'a.id',
                's.date',
                'a.cod_fab',
                'a.description as nombre_articulo',
                'sa.quantity as cantidad',
                'sa.unit_price as precio_venta',
                'sa.subtotal as total_venta',
                DB::raw('(sa.quantity * sa.costo_neto) as total_costo'),
                DB::raw('(sa.subtotal - (sa.quantity * sa.costo_neto)) as utilidad'),
                'sa.costo_neto as costo_unitario',
                DB::raw("CONCAT(u.firstname, ' ', u.lastname) as vendedor")
            );

        // Apply optional filters
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

        if ($article_id !== null) {
            $query->where('sa.article_id', $article_id);
        }

        if ($description !== null && $description !== '') {
            $query->where(function ($q) use ($description) {
                $q->where('a.description', 'like', '%' . $description . '%')
                    ->orWhere('a.id', 'like', '%' . $description . '%')
                    ->orWhere('a.cod_fab', 'like', '%' . $description . '%');
            });
        }

        // Order by article code
        $query->orderBy('a.cod_fab')
            ->orderBy('s.date');

        return $query->paginate($perPage);
    }

    public function getArticleIdSold(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, int $perPage = 10)
    {
        $customerNameExpression = "COALESCE(NULLIF(c.company_name, ''), NULLIF(TRIM(CONCAT_WS(' ', c.name, c.lastname, c.second_lastname)), ''))";

        $query = DB::table('sales as s')
            ->join('sale_article as sa', 's.id', '=', 'sa.sale_id')
            ->join('branches as b', 's.branch_id', '=', 'b.id')
            ->join('document_types as dt', 's.document_type_id', '=', 'dt.id')
            ->join('customers as c', 's.customer_id', '=', 'c.id')
            ->join('currency_types as ct', 's.currency_type_id', '=', 'ct.id')
            ->where('s.company_id', $company_id)
            ->where('sa.article_id', $article_id)
            ->where('s.status', 1)
            ->whereIn('s.document_type_id', [1, 3]) // Facturas y Boletas
            ->select(
                'b.name as sucursal',
                'dt.abbreviation as tipo_documento',
                's.serie',
                's.document_number as correlativo',
                's.date as fecha_venta',
                DB::raw("{$customerNameExpression} as customer_name"),
                'sa.quantity as cantidad',
                'ct.commercial_symbol as tipo_moneda',
                'sa.unit_price as precio_venta',
                's.parallel_rate as tipo_cambio',
                DB::raw('CASE 
                    WHEN s.currency_type_id = 1 THEN sa.subtotal 
                    WHEN s.currency_type_id = 2 THEN sa.subtotal * s.parallel_rate 
                    ELSE sa.subtotal 
                END as importe_soles')
            );

        // Apply optional filters
        if ($branch_id !== null) {
            $query->where('s.branch_id', $branch_id);
        }

        if ($start_date !== null) {
            $query->where('s.date', '>=', $start_date);
        }

        if ($end_date !== null) {
            $query->where('s.date', '<=', $end_date);
        }

        // Order by date descending (most recent first)
        $query->orderBy('s.date', 'desc')
            ->orderBy('s.id', 'desc');

        return $query->paginate($perPage);
    }

    public function getArticleIdPurchase(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, int $perPage = 10)
    {
        $query = DB::table('purchase as p')
            ->join('detail_purchase_guides as dpg', 'p.id', '=', 'dpg.purchase_id')
            ->join('branches as b', 'p.branch_id', '=', 'b.id')
            ->join('document_types as dt', 'p.document_type_id', '=', 'dt.id')
            ->leftJoin('customers as c', 'p.supplier_id', '=', 'c.id')
            ->where('p.company_id', $company_id)
            ->where('dpg.article_id', $article_id)
            ->select(
                'b.name as sucursal',
                'dt.abbreviation as tipo_documento',
                'p.reference_serie',
                'p.reference_correlative as correlativo',
                'p.date as fecha_compra',
                DB::raw('CASE 
                    WHEN c.company_name IS NOT NULL AND c.company_name != "" THEN c.company_name
                    WHEN c.name IS NOT NULL THEN TRIM(CONCAT(COALESCE(c.name, ""), " ", COALESCE(c.lastname, ""), " ", COALESCE(c.second_lastname, "")))
                    ELSE "Sin proveedor"
                END as proveedor'),
                'dpg.cantidad',
                DB::raw('CASE 
                    WHEN p.currency = 1 THEN "S/" 
                    WHEN p.currency = 2 THEN "$" 
                    ELSE "S/" 
                END as tipo_moneda'),
                'dpg.precio_costo as precio_compra',
                'p.exchange_type as tipo_cambio',
                DB::raw('CASE 
                    WHEN p.currency = 1 THEN ROUND(dpg.total, 4) 
                    WHEN p.currency = 2 THEN ROUND(dpg.total * p.exchange_type, 4) 
                    ELSE ROUND(dpg.total, 4) 
                END as importe_soles')
            );

        // Apply optional filters
        if ($branch_id !== null) {
            $query->where('p.branch_id', $branch_id);
        }

        if ($start_date !== null) {
            $query->where('p.date', '>=', $start_date);
        }

        if ($end_date !== null) {
            $query->where('p.date', '<=', $end_date);
        }

        // Order by date descending (most recent first)
        $query->orderBy('p.date', 'desc')
            ->orderBy('p.id', 'desc');

        return $query->paginate($perPage);
    }
    public function getListaPrecio(int $p_codma, ?int $p_codcategoria, ?int $p_status, ?int $p_moneda, ?int $p_orden)
    {
        $resultado = DB::select(
            'CALL sp_lista_precios(?,?,?,?,?)',
            [$p_codma, $p_codcategoria, $p_status, $p_moneda, $p_orden]
        );


        return $resultado;
    }
    public function rankingAnualCliente(int $p_company_id, ?int $p_branch_id, int $p_customer_id, int $p_annio, int $p_currency_type_id, int $p_document_type_id)
    {
        $resultado = DB::select(
            'CALL sp_ranking_anual_cliente(?,?,?,?,?,?)',
            [$p_company_id, $p_branch_id, $p_customer_id, $p_annio, $p_currency_type_id, $p_document_type_id]
        );

        return $resultado;
    }
    public function consultas_ventas(int $p_compania_id, ?int $p_branch_id, ?int $p_document_type_id, ?string $p_serie, ?string $p_correlativo, ?string $p_fecha1, ?string $p_fecha2, ?int $p_customer_id, ?int $p_vendedor_id, ?int $p_status_id)
    {

        $resultado = DB::select(
            'CALL sp_consultas_ventas(?,?,?,?,?,?,?,?,?,?)',
            [$p_compania_id, $p_branch_id, $p_document_type_id, $p_serie, $p_correlativo, $p_fecha1, $p_fecha2, $p_customer_id, $p_vendedor_id, $p_status_id]
        );

        return $resultado;
    }
    public function consultaReporteVentas(int $company_id, int $branch_id, int $cod_article, int $categoria, int $marca, bool $is_igv, int $cod_vendedor, string $fecha_inicio, string $fecha_fin)
    {
        $query = DB::table('sales as s')
            ->join('sale_article as sa', 's.id', '=', 'sa.sale_id')
            ->join('articles as a', 'sa.article_id', '=', 'a.id')
            ->join('branches as b', 's.branch_id', '=', 'b.id')
            ->join('customers as c', 's.customer_id', '=', 'c.id')
            ->leftJoin('categories as cat', 'a.category_id', '=', 'cat.id')
            ->leftJoin('brands as br', 'a.brand_id', '=', 'br.id')
            ->leftJoin('users as u', 's.user_sale_id', '=', 'u.id')
            ->where('s.company_id', $company_id)
            ->where('s.status', 1);

        // Conditional filters
        if ($branch_id !== 0) {
            $query->where('s.branch_id', $branch_id);
        }

        if ($cod_article !== 0) {
            $query->where('sa.article_id', $cod_article);
        }

        if ($categoria !== 0) {
            $query->where('a.category_id', $categoria);
        }

        if ($marca !== 0) {
            $query->where('a.brand_id', $marca);
        }

        if ($cod_vendedor !== 0) {
            $query->where('s.user_sale_id', $cod_vendedor);
        }

        if ($fecha_inicio !== '' && $fecha_fin !== '') {
            $query->whereBetween('s.date', [$fecha_inicio, $fecha_fin]);
        }
        if (empty($fecha_inicio) && empty($fecha_fin)) {
            $query->where('s.date', '<=', date('Y-m-d'));
        }
        if ($is_igv) {
            $query->where('a.igv_applicable', 0);
        }
        $query->leftJoin('note_reasons as nr', 's.note_reason_id', '=', 'nr.id')
            ->whereNotIn('s.document_type_id', [8, 16]);

        return $query->select(
            'a.cod_fab as CODIGO',
            'a.description as DESCRIPCION',
            DB::raw('SUM(sa.quantity * CASE 
                WHEN s.document_type_id = 7 AND nr.stock = 1 THEN -1 
                WHEN s.document_type_id = 7 AND nr.stock = 2 THEN 0 
                ELSE 1 
            END) as CANTIDAD'),
            'mu.abbreviation as UDM',
            DB::raw('SUM(CASE 
                WHEN s.currency_type_id = 1 THEN sa.subtotal * CASE WHEN s.document_type_id = 7 THEN -1 ELSE 1 END 
                ELSE 0 
            END) as "S/"'),
            DB::raw('SUM(CASE 
                WHEN s.currency_type_id = 2 THEN sa.subtotal * CASE WHEN s.document_type_id = 7 THEN -1 ELSE 1 END 
                ELSE 0 
            END) as "US$"')
        )
            ->join('measurement_units as mu', 'a.measurement_unit_id', '=', 'mu.id')
            ->groupBy('s.company_id', 'sa.article_id', 'a.cod_fab', 'a.description', 'mu.abbreviation')
            ->get();
    }
}
