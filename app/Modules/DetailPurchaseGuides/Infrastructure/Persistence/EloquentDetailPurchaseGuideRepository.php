<?php

namespace App\Modules\DetailPurchaseGuides\Infrastructure\Persistence;

use App\Modules\DetailPurchaseGuides\Domain\Entities\DetailPurchaseGuide;
use App\Modules\DetailPurchaseGuides\Domain\Interface\DetailPurchaseGuideRepositoryInterface;
use App\Modules\DetailPurchaseGuides\Infrastructure\Models\EloquentDetailPurchaseGuide;

class EloquentDetailPurchaseGuideRepository implements DetailPurchaseGuideRepositoryInterface
{
    public function findAll(): array
    {
        $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::all();
        return $eloquentDetailPurchaseGuide->map(function ($item) {
            return new DetailPurchaseGuide(
                id: $item->id,
                article_id: $item->article_id,
                purchase_id: $item->purchase_id,
                description: $item->description,
                cantidad: $item->cantidad,
                precio_costo: $item->precio_costo,
                descuento: $item->descuento,
                sub_total: $item->sub_total,
                total: $item->total,
            );
        })->toArray();
    }
    public function findById(int $id): array
    {
        $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::where('purchase_id', $id)->get();
        if (!$eloquentDetailPurchaseGuide) {
            return [];
        }

        return $eloquentDetailPurchaseGuide->map(function ($item) {
            return new DetailPurchaseGuide(
                id: $item->id,
                article_id: $item->article_id,
                purchase_id: $item->purchase_id,
                description: $item->description,
                cantidad: $item->cantidad,
                precio_costo: $item->precio_costo,
                descuento: $item->descuento,
                sub_total: $item->sub_total,
                total: $item->total,
            );
        })->toArray();
    }
    public function save(DetailPurchaseGuide $detailPurchaseGuide): ?DetailPurchaseGuide
    {
        $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::create([
            'article_id' => $detailPurchaseGuide->getArticleId(),
            'purchase_id' => $detailPurchaseGuide->getPurchaseId(),
            'description' => $detailPurchaseGuide->getDescription(),
            'cantidad' => $detailPurchaseGuide->getCantidad(),
            'precio_costo' => $detailPurchaseGuide->getPrecioCosto(),
            'descuento' => $detailPurchaseGuide->getDescuento(),
            'sub_total' => $detailPurchaseGuide->getSubTotal(),
            'total' => $detailPurchaseGuide->getTotal(),
        ]);
        return new DetailPurchaseGuide(
            id: $eloquentDetailPurchaseGuide->id,
            article_id: $eloquentDetailPurchaseGuide->article_id,
            purchase_id: $eloquentDetailPurchaseGuide->purchase_id,
            description: $eloquentDetailPurchaseGuide->description,
            cantidad: $eloquentDetailPurchaseGuide->cantidad,
            precio_costo: $eloquentDetailPurchaseGuide->precio_costo,
            descuento: $eloquentDetailPurchaseGuide->descuento,
            sub_total: $eloquentDetailPurchaseGuide->sub_total,
            total: $eloquentDetailPurchaseGuide->total,
        );
    }
    public function deletedBy(int $id): void
    {
        EloquentDetailPurchaseGuide::where('purchase_id', $id)->delete();
    }
}
