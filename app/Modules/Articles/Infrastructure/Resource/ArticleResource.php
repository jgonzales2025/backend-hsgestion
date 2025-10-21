<?php
namespace App\Modules\Articles\Infrastructure\Resource;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id' => $this->getId(),
            'cod_fab' => $this->getCodFab(),
            'description' => $this->getDescription(),
            'weight' => $this->getWeight(),
            'with_deduction' => $this->getWithDeduction(),
            'series_enabled' => $this->getSeriesEnabled(),


            // 'measurement_unit' => $this->getMeasurementUnitId(),

            'brand' => [
                'id' => $this->getBrand()->getId(),
                'name' => $this->getBrand()->getName(),
                'status' => ($this->getBrand()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'category' => [
                'id' => $this->resource->getCategory()->getId(),
                'name' => $this->resource->getCategory()->getName(),
                'status' => ($this->resource->getCategory()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],

            'currencyType' => [
                'id' => $this->resource->getCurrencyType()->getId(),
                'name' => $this->resource->getCurrencyType()->getName(),
            ],
            'measurementUnit' => [
                'id' => $this->resource->getMeasurementUnit()->getId(),
                'name' => $this->resource->getMeasurementUnit()->getName(),
                'status' => ($this->resource->getMeasurementUnit()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'subCategory' => [
                'id' => $this->resource->getSubCategory()->getId(),
                'name' => $this->resource->getSubCategory()->getName(),
                'status' => ($this->resource->getSubCategory()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            //           'company' =>  [
            //             'id' => $this->resource->getCompany()->getId(),
            //   ],

            // Resto de campos planos
            'location' => $this->getLocation(),
            'warranty' => $this->getWarranty(),
            'tariff_rate' => $this->getTariffRate(),
            'igv_applicable' => $this->getIgvApplicable(),
            'plastic_bag_applicable' => $this->getPlasticBagApplicable(),
            'min_stock' => $this->getMinStock(),

            'purchase_price' => $this->getPurchasePrice(),
            'public_price' => $this->getPublicPrice(),
            'distributor_price' => $this->getDistributorPrice(),
            'authorized_price' => $this->getAuthorizedPrice(),
            'public_price_percent' => $this->getPublicPricePercent(),
            'distributor_price_percent' => $this->getDistributorPricePercent(),
            'authorized_price_percent' => $this->getAuthorizedPricePercent(),
            'status' => ($this->getStatus()) == 1 ? "Activo" : "Inactivo",
            'precioIGv' => $this->calculatePrecioIGV(),
            'venta' => $this->getVenta() == true ? 'Activo' : 'Inactivo',
   'company' => [
    'id' => $this->resource->getCompany()->getId(),
    'status' => ($this->resource->getCompany()->getStatus()) == 1 ? 'Activo' : 'Inactivo',
    'branches' => EloquentBranch::where('cia_id', $this->resource->getCompany()->getId())
        ->pluck('id'),
],
            'image_url' => $this->getImageURL()
        ];
    }
}
