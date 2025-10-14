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
            'short_description' => $this->getShortDescription(),
            'weight' => $this->getWeight(),
            'with_deduction' => $this->getWithDeduction(),
            'series_enabled' => $this->getSeriesEnabled(),


            'measurement_unit' => $this->getMeasurementUnitId(),

             'brand' => $this->getBrand(),

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
            'status' => $this->getStatus(),
            'user_id' => $this->getUserId(),
        ];
    }
}
