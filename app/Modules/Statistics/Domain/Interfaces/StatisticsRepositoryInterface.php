<?php

namespace App\Modules\Statistics\Domain\Interfaces;

interface StatisticsRepositoryInterface
{
    public function getCustomerConsumedItems(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId);

    public function getCustomerConsumedItemsPaginated(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $customerId, int $perPage = 15);

    public function getArticlesSold(int $company_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id, ?int $article_id, ?string $description, int $perPage = 15);

    public function getArticleIdSold(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id);

    public function getArticleIdPurchase(int $company_id, int $article_id, ?int $branch_id, ?string $start_date, ?string $end_date, ?int $category_id, ?int $brand_id);

    public function getListaPrecio(int $p_codma, ?int $p_codcategoria, ?int $p_status, ?int $p_moneda, ?int $p_orden);

    public function rankingAnualCliente(int $p_company_id, ?int $p_branch_id, int $p_customer_id, int $p_annio, int $p_currency_type_id, int $p_document_type_id);

    public function consultas_ventas(int $p_company_id, ?int $p_branch_id, ?int $p_document_type_id, ?string $p_serie, ?string $p_correlativo, ?string $p_fecha1, ?string $p_fecha2, ?int $p_customer_id, ?int $p_vendedor_id, ?int $p_status_id);
    public function consultaReporteVentas(int $company_id, int $branch_id, int $cod_article, int $categoria, int $marca, bool $is_igv, int $cod_vendedor, string $fecha_inicio, string $fecha_fin);
}
