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
                cantidad_update: $item->cantidad_update,
                process_status: $item->process_status,
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
                cantidad_update: $item->cantidad_update,
                process_status: $item->process_status,
            );
        })->toArray();
    }

    public function findByDetailId(int $id): ?DetailPurchaseGuide
    {
        $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::find($id);
        if (!$eloquentDetailPurchaseGuide) {
            return null;
        }

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
            cantidad_update: $eloquentDetailPurchaseGuide->cantidad_update,
            process_status: $eloquentDetailPurchaseGuide->process_status,
        );
    }
    public function save(DetailPurchaseGuide $detailPurchaseGuide): ?DetailPurchaseGuide
    {
        $data = [
            'article_id' => $detailPurchaseGuide->getArticleId(),
            'purchase_id' => $detailPurchaseGuide->getPurchaseId(),
            'description' => $detailPurchaseGuide->getDescription(),
            'cantidad' => $detailPurchaseGuide->getCantidad(),
            'precio_costo' => $detailPurchaseGuide->getPrecioCosto(),
            'descuento' => $detailPurchaseGuide->getDescuento(),
            'sub_total' => $detailPurchaseGuide->getSubTotal(),
            'total' => $detailPurchaseGuide->getTotal(),
            'cantidad_update' => $detailPurchaseGuide->getCantidadUpdate(),
            'process_status' => $detailPurchaseGuide->getProcessStatus(),
        ];

        if ($detailPurchaseGuide->getId()) {
            // Update existing record
            $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::find($detailPurchaseGuide->getId());
            if (!$eloquentDetailPurchaseGuide) {
                return null;
            }
            $eloquentDetailPurchaseGuide->update($data);
        } else {
            // Create new record
            $eloquentDetailPurchaseGuide = EloquentDetailPurchaseGuide::create($data);
        }

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
            cantidad_update: $eloquentDetailPurchaseGuide->cantidad_update,
            process_status: $eloquentDetailPurchaseGuide->process_status,
        );
    }
    public function deletedBy(int $id): void
    {
        EloquentDetailPurchaseGuide::where('purchase_id', $id)->delete();
    }
}
