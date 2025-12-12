<?php

namespace App\Modules\Articles\Infrastructure\Resource;

use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use Illuminate\Http\Resources\Json\JsonResource;

class EloquentArticleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->resource->id,
            'cod_fab' => $this->resource->cod_fab,
            'description' => $this->resource->description,
            'weight' => $this->resource->weight,
            'with_deduction' => $this->resource->with_deduction,
            'series_enabled' => $this->resource->series_enabled,
            'brand' => [
                'id' => $this->resource->id,
                'name' => $this->resource->brand->name,
                'status' => ($this->resource->brand->status) == 1 ? 'Activo' : 'Inactivo',
            ],
            'category' => [
                'id' => $this->resource->id,
                'name' => $this->resource->category->name,
                'status' => ($this->resource->category->status) == 1 ? 'Activo' : 'Inactivo',
            ],

            'currencyType' => [
                'id' => $this->resource->id,
                'name' => $this->resource->currencyType->name,
                'commercial_symbol' => $this->resource->currencyType->commercial_symbol,
            ],
            'measurementUnit' => [
                'id' => $this->resource->id,
                'name' => $this->resource->measurementUnit->name,
                'status' => ($this->resource->measurementUnit->status) == 1 ? 'Activo' : 'Inactivo',
            ],
            'subCategory' => [
                'id' => $this->resource->id,
                'name' => $this->resource->subCategory->name,
                'status' => ($this->resource->subCategory->status) == 1 ? 'Activo' : 'Inactivo',
            ],
            'location' => $this->resource->location,
            'warranty' => $this->resource->warranty,
            'tariff_rate' => $this->resource->tariff_rate,
            'igv_applicable' => $this->resource->igv_applicable,
            'plastic_bag_applicable' => $this->resource->plastic_bag_applicable,
            'min_stock' => $this->resource->min_stock, 
            'purchase_price' => $this->resource->purchase_price,
            'public_price' => $this->resource->public_price,
            'distributor_price' => $this->resource->distributor_price,
            'authorized_price' => $this->resource->authorized_price,
            'public_price_percent' => $this->resource->public_price_percent,
            'distributor_price_percent' => $this->resource->distributor_price_percent,
            'authorized_price_percent' => $this->resource->authorized_price_percent,
            'status' => ($this->resource->status) == 1 ? "Activo" : "Inactivo",
            'venta' => $this->resource->venta == true ? 'Activo' : 'Inactivo',
            'company' => [
                'id' => $this->resource->company->id,
                'status' => ($this->resource->company->status) == 1 ? 'Activo' : 'Inactivo',
                'branches' => EloquentBranch::where('cia_id', $this->resource->company->id)
                    ->pluck('id'),
            ], 

            'image_url' => $this->resource->image_url
                ? url($this->resource->image_url)
                : '',
            'is_visible' => ($this->resource->state_modify_article) === true ? 'Activo' : 'Inactivo',
            'state_modify_article' => $this->resource->state_modify_article,
            'is_combo' => $this->resource->is_combo ,
        ];
    }
}