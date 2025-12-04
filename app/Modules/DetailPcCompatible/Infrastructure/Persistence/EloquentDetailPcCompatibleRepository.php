<?php

namespace App\Modules\DetailPcCompatible\Infrastructure\Persistence;

use App\Modules\DetailPcCompatible\Infrastructure\Models\EloquentDetailPcCompatible;
use App\Modules\DetailPcCompatible\Domain\Entities\DetailPcCompatible;
use App\Modules\DetailPcCompatible\Domain\Interface\DetailPcCompatibleRepositoryInterface;

class EloquentDetailPcCompatibleRepository implements DetailPcCompatibleRepositoryInterface
{
    public function findAll(): array
    {
        $detailPcCompatibles = EloquentDetailPcCompatible::with(['articleMajor', 'articleAccessory'])->get();
        return $detailPcCompatibles->map(function ($detail) {
            return new DetailPcCompatible(
                id: $detail->id,
                article_major_id: $detail->article_major_id,
                article_accesory_id: $detail->article_accesory_id,
                status: $detail->status
            );
        })->toArray();
    }
    public function findById(int $id): ?DetailPcCompatible
    {
        $detail = EloquentDetailPcCompatible::with(['articleMajor', 'articleAccessory'])->find($id);

        if (!$detail) {
            return null;
        }

        return new DetailPcCompatible(
            id: $detail->id,
            article_major_id: $detail->article_major_id,
            article_accesory_id: $detail->article_accesory_id,
            status: $detail->status
        );
    }
    public function create(DetailPcCompatible $data): ?DetailPcCompatible
    {
        $detail = EloquentDetailPcCompatible::create([
            'article_major_id' => $data->getArticleMajorId(),
            'article_accesory_id' => $data->getArticleAccesoryId(),
            'status' => $data->getStatus()
        ]);

        // Load relationships
        $detail->load(['articleMajor', 'articleAccessory']);

        return new DetailPcCompatible(
            id: $detail->id,
            article_major_id: $detail->article_major_id,
            article_accesory_id: $detail->article_accesory_id,
            status: $detail->status
        );
    }
    public function update(DetailPcCompatible $data): ?DetailPcCompatible
    {
        $detail = EloquentDetailPcCompatible::find($data->getId());

        if (!$detail) {
            return null;
        }

        $detail->update([
            'article_major_id' => $data->getArticleMajorId(),
            'article_accesory_id' => $data->getArticleAccesoryId(),
            'status' => $data->getStatus()
        ]);

        // Load relationships
        $detail->load(['articleMajor', 'articleAccessory']);

        return new DetailPcCompatible(
            id: $detail->id,
            article_major_id: $detail->article_major_id,
            article_accesory_id: $detail->article_accesory_id,
            status: $detail->status
        );
    }

    public function findAllArticles(int $id): array
    {
        $detailPcCompatibles = EloquentDetailPcCompatible::with(['articleMajor', 'articleAccessory'])->where('article_major_id', $id)->get();
        return $detailPcCompatibles->map(function ($detail) {
            return new DetailPcCompatible(
                id: $detail->id,
                article_major_id: $detail->article_major_id,
                article_accesory_id: $detail->article_accesory_id,
                status: $detail->status
            );
        })->toArray();
    }

    public function deleteByArticleMajorId(int $articleMajorId): bool
    {
        return EloquentDetailPcCompatible::where('article_major_id', $articleMajorId)->delete() !== false;
    }
}
