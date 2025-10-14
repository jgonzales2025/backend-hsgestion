<?php
namespace App\Modules\Articles\Infrastructure\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id' => $this->getId(),
            'cod_fab' => $this->getCodFab(),
            'description' => $this->getDescription(),
            // 'short_description' => $this->getShortDescription(),
            'weight' => $this->getWeight(),
            'with_deduction' => $this->getWithDeduction(),
            'series_enabled' => $this->getSeriesEnabled(),


            // 'measurement_unit' => $this->getMeasurementUnitId(),

            'brand' => $this->getBrand() ? [
                'id' => $this->getBrand()['id'] ?? null,
                'name' => $this->getBrand()['name'] ?? null,
                'status' => ($this->getBrand()['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo',
            ] : null,
            'category' => $this->getCategory() ? [
                'id' => $this->getCategory()['id'] ?? null,
                'name' => $this->getCategory()['name'] ?? null,
                'status' => ($this->getCategory()['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo',
            ] : null,
            'currencyType' => $this->getCurrencyType() ? [
                'id' => $this->getCurrencyType()['id'] ?? null,
                'name' => $this->getCurrencyType()['name'] ?? null,
                'status' => ($this->getCurrencyType()['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo',
            ] : null,
             'measurementUnit' => $this->getMeasurementUnit() ? [
                'id' => $this->getMeasurementUnit()['id'] ?? null,
                'name' => $this->getMeasurementUnit()['name'] ?? null,
                'status' => ($this->getMeasurementUnit()['status'] ?? 0) == 1 ? 'Activo' : 'Inactivo',
            ] : null,

            // Resto de campos planos
            'category_id' => $this->getCategoryId(),
            'location' => $this->getLocation(),
            'warranty' => $this->getWarranty(),
            'tariff_rate' => $this->getTariffRate(),
            'igv_applicable' => $this->getIgvApplicable(),
            'plastic_bag_applicable' => $this->getPlasticBagApplicable(),
            'min_stock' => $this->getMinStock(),
            'currency_type_id' => $this->getCurrencyTypeId(),
            'cost_to_price_percent' => $this->getCostToPricePercent(),
            'purchase_price' => $this->getPurchasePrice(),
            'public_price' => $this->getPublicPrice(),
            'distributor_price' => $this->getDistributorPrice(),
            'authorized_price' => $this->getAuthorizedPrice(),
            'public_price_percent' => $this->getPublicPricePercent(),
            'distributor_price_percent' => $this->getDistributorPricePercent(),
            'authorized_price_percent' => $this->getAuthorizedPricePercent(),
            'status' => ($this->getStatus()) == 1 ? "Activo" : "Inactivo",
            'precioIGv' => $this->getPrecioIGV(),
             'subCategory' =>$this->getSubCategoria(),
            'venta'=>($this->getVenta()) == true ? "Activo" : "Inactivo" ,
            // 'user_id' => $this->getUserId(),
        ];
    }
}
