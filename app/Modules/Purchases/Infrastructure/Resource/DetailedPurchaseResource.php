<?php

namespace App\Modules\Purchases\Infrastructure\Resource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedPurchaseResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $detail = $this->resource;

        // Ensure relationships are loaded or handle nulls
        $purchase = $detail->purchase;
        $article = $detail->article;

        $currencyId = $purchase?->currency ?? 1;
        $exchangeType = (float) ($purchase?->exchange_type ?? 1);
        $totalItem = (float) ($detail->total ?? 0);

        $totalSoles = $currencyId == 1 ? number_format($totalItem, 2, '.', '') : '';
        $totalDolares = $currencyId == 2 ? number_format($totalItem, 2, '.', '') : '';

        return [
            'SUCURSAL' => $purchase?->branches?->name ?? '',
            'SERIE' => $purchase?->reference_serie ?? '',
            'NUMERO' => $purchase?->reference_correlative ?? '',
            'FECHA' => $purchase?->date ?? '',
            'PROVEEDOR' => $purchase?->customers?->company_name ?:
                trim(($purchase?->customers?->name ?? '') . ' ' . ($purchase?->customers?->lastname ?? '') . ' ' . ($purchase?->customers?->second_lastname ?? '')),
            'MOTIVO' => $this->getMotivo($purchase),
            'CODIGO' => $article?->cod_fab ?? '',
            'ARTICULO' => $article?->description ?? $detail->description,
            'MARCA' => $article?->brand?->name ?? '',
            'CATEGORIA' => $article?->category?->name ?? '',
            'SUBCATEGORIA' => $article?->subCategory?->name ?? $article?->subcategory?->name ?? '',
            'CANTIDAD' => $detail->cantidad ?? 0,
            'T/M' => $currencyId == 1 ? 'S/' : 'US$',
            'C.UNIT.' => number_format($detail->precio_costo ?? 0, 2, '.', ''),
            'TOT.SOLES' => $totalSoles,
            'TOT.DOLARES' => $totalDolares,
        ];
    }

    private function getMotivo($purchase)
    {
        if ($purchase && $purchase->shoppingIncomeGuide && count($purchase->shoppingIncomeGuide) > 0) {
            $guide = $purchase->shoppingIncomeGuide[0]->entryGuide ?? null;
            return $guide?->ingressReason?->description ?? 'COMPRA';
        }
        return 'COMPRA';
    }
}
