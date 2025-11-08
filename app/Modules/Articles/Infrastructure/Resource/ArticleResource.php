<?php
namespace App\Modules\Articles\Infrastructure\Resource;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\ReferenceCode\Infrastructure\Models\EloquentReferenceCode;
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
            'brand' => [
                'id' => $this->getBrand()?->getId(),
                'name' => $this->getBrand()?->getName(),
                'status' => ($this->getBrand()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'category' => [
                'id' => $this->resource->getCategory()?->getId(),
                'name' => $this->resource->getCategory()?->getName(),
                'status' => ($this->resource->getCategory()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],

            'currencyType' => [
                'id' => $this->resource->getCurrencyType()?->getId(),
                'name' => $this->resource->getCurrencyType()?->getName(),
                'commercial_symbol' => $this->resource->getCurrencyType()?->getCommercialSymbol(),
            ],
            'measurementUnit' => [
                'id' => $this->resource->getMeasurementUnit()?->getId(),
                'name' => $this->resource->getMeasurementUnit()?->getName(),
                'status' => ($this->resource->getMeasurementUnit()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
            'subCategory' => [
                'id' => $this->resource->getSubCategory()?->getId(),
                'name' => $this->resource->getSubCategory()?->getName(),
                'status' => ($this->resource->getSubCategory()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
            ],
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
            'venta' => $this->getVenta() == true ? 'Activo' : 'Inactivo',
            'company' => [
                'id' => $this->resource->getCompany()?->getId(),
                'status' => ($this->resource->getCompany()?->getStatus()) == 1 ? 'Activo' : 'Inactivo',
                'branches' => EloquentBranch::where('cia_id', $this->resource->getCompany()?->getId())
                    ->pluck('id'),
            ],

'reference_codes' => EloquentReferenceCode::where('article_id', $this->resource->getId())
    ->when($request->has('lastname'), function ($query) use ($request) {
        $query->where('ref_code', 'like', '%' . $request->query('lastname') . '%');
    })
    ->get()
    ->map(function ($code) {
        return [
            'id' => $code->id,
            'ref_code' => $code->ref_code,
            'status' => $code->status == 1 ? 'Activo' : 'Inactivo',
            'date_at' => $code->date_at,
        ];
    })
    ->toArray(),

            'image_url' => $this->resource->getImageURL()
                ? url($this->resource->getImageURL())
                : '',
            'is_visible' => ($this->resource->getstateModifyArticle()) === true ? 'Activo' : 'Inactivo',
        ];

    }
}
